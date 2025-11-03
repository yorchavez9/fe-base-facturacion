<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Inflector;
use Endroid\QrCode\QrCode;
use Picqer\Barcode;
use Dompdf\Dompdf;
use Cake\Http\ServerRequest;

/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemsController extends AppController
{
    private $ruta_img_codigos;
    private $ruta_img_galeria;
    private $ruta_grupo;
    private $tipo_monedas = [
      'PEN' => 'PEN',
      'USD' => 'USD'
    ];

    protected $Items = null;
    protected $ItemRels = null;
    protected $ItemCategorias = null;
    protected $ItemMarcas = null;
    protected $Stock = null;
    protected $Ventas = null;
    protected $VentaRegistros = null;
    protected $StockHistorial = null;

    public function initialize(): void {
        parent::initialize();
        $this->Items = $this->fetchTable("Items");
        $this->ItemRels = $this->fetchTable("ItemRels");
        $this->ItemCategorias = $this->fetchTable("ItemCategorias");
        $this->ItemMarcas = $this->fetchTable("ItemMarcas");
        $this->Stock = $this->fetchTable("Stock");
        $this->StockHistorial = $this->fetchTable("StockHistorial");

        $this->VentaRegistros = $this->fetchTable('VentaRegistros');

        $this->ruta_img_codigos = $this->getPathBarCode();

        $this->Authentication->allowUnauthenticated([
            'getOne',
            'getAll',
            'save',
        ]);
    }

    public function beforeRender(\Cake\Event\EventInterface $event) {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }

    public function index()
    {
        $q = $this->request->getQuery('q');
        $cid = $this->request->getQuery('categoria_id');
        $mid = $this->request->getQuery('marca_id');

        $exp = $this->request->getQuery('exportar');
       
        $items = $this->Items->find()->where([
            'OR'    =>
            [
                'info_busqueda LIKE ' => "%{$q}%",
                'descripcion_alternativa LIKE'  =>  "%{$q}%"
            ] ,
             'unidad <> ' => 'ZZ'
            ]);
        if ($cid != ''){
            $items = $items->andWhere(['Items.categoria_id' => $cid]);
        }
        if ($mid != ''){
            $items = $items->andWhere(['Items.marca_id' => $mid]);
        }

        if ($exp == 1){
            $items_exp = $this->Items->find();
            $items_exp->select([                
                'stock_general' => $items_exp->func()->sum('Stock.Stock')
            ])
            ->where(['unidad <> ' => 'ZZ'])
            ->enableAutoFields()
            ->leftJoinWith('Stock')
            ->distinct(['Items.id']);
            return $this->exportar($items_exp);
            exit;
        }

        $items = $this->paginate($items, ['maxLimit' => 10]);
        $totales = 0.00;

        foreach ($items as $i){
            $marca = $this->ItemMarcas->find()->where(['id' => ($i->marca_id ?? "0")])->first();
            $categoria = $this->ItemCategorias->find()->where(['id' => ($i->categoria_id ?? "0")])->first();
            $i->marca_nombre = ($marca) ? $marca->nombre : "Sin Marca";
            $i->categoria_nombre = ($categoria) ? $categoria->nombre : "Sin Categoría";
            $stock = $this->Stock->find()->where(['item_id' => $i->id])->first();
            if($stock) {
                $i_total = $i->precio_venta * $stock->stock;
                $totales += $i_total;
            }
        }

        $this->set(compact('items'));
        $this->set('_serialize', ['productos']);

        $marcas = $this->ItemMarcas->find('list');
        $categorias = $this->ItemCategorias->find('list');
        $this->set("marcas", $marcas);
        $this->set("categorias", $categorias);

        // links que se muestra en la vista
        $top_acciones = [
            'title'  =>  "Productos",
            'links' =>  [
                'btn1'  =>  [
                    'name'    =>     "<i class='fas fa-plus fa-fw'></i> Nuevo Producto",
                    'params'    =>     [
                        'controller' =>  'Items',
                        'action'     =>  'Add',
                    ]

                ],
                // 'btn2'  =>  [
                //     'name'    =>     "<i class='fas fa-plus fa-fw'></i> Cargar desde Excel",
                //     'params'    =>     [
                //         'controller' =>  'Items',
                //         'action'     =>  'AddExcel',
                //     ]

                // ],

            ]
        ];

        $this->set("cid", $cid);
        $this->set("mid", $mid);
        $this->set("view_title", "Productos");
        $this->set("top_links", $top_acciones);
        $this->set("q", $q);
        $this->set("totales", $totales);
    }



    public function add()
    {
        $producto = $this->Items->newEmptyEntity();
        if ($this->request->is('post', 'put', 'patch')) {
            $data = $this->request->getData();
            $producto = $this->Items->patchEntity($producto, $data);
            $item = $this->saveItem($data);
            if ($item) {
                $item->img_ruta = $this->saveUploadedFile($data['file_img_ruta'], "producto_" . $item->id, 'media/items');
                $item = $this->Items->save($item);

                $this->Flash->success('El producto se ha registrado con éxito.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('Ha ocurrido un error, por favor, intente nuevamente.');
            }
        }


        $categorias = $this->ItemCategorias->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);
        $marcas = $this->ItemMarcas->find('list', [
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);

        // links que se muestra en la vista
        $top_acciones = [
            'title' =>  "Nuevo Item",
            'links' =>  [
                'btnBack'   =>  [
                    'name'  =>  "<i class='fas fa-chevron-left'></i> Regresar al Listado",
                    'params'    =>  ['action' => 'index']
                ]
            ]
        ];
        $this->set("categorias", $categorias);
        $this->set("marcas", $marcas);
        $this->set("top_links", $top_acciones);
        $this->set("view_title", "Nuevo Producto");
        $this->set("producto", $producto);
        $this->set('tipo_monedas',$this->tipo_monedas);
        $this->set('_serialize', ['producto']);

    }
public function edit($id = null)
    {
        $producto = $this->Items->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            // print_r($data); exit;
            $producto = $this->Items->patchEntity($producto,$data );
            $producto->info_busqueda = $this->generarInfoBusqueda($producto);
            if(isset($data['reponer_stock'])){
                $producto->reponer_stock = $data["reponer_stock"];
            }
            //$producto->valor_unitario = $this->regularizarValorUnitario($producto->precio_venta,$producto->inc_igv);

            $producto = $this->Items->save($producto);
            if ($producto) {
                $producto->img_ruta = $this->saveUploadedFile($data['file_img_ruta'], "producto_" . $producto->id, 'media/items');

                if ($producto->img_ruta != "") {
                    $producto = $this->Items->save($producto);
                }

                $this->Flash->success(__('El producto se ha actualizado con éxito.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Ha ocurrido un error, por favor, intente nuevamente.'));
            }
        }

        // Categoria
        $categorias = $this->ItemCategorias->find("list");
        $marcas = $this->ItemMarcas->find("list");
        $top_acciones = [
            'title' =>  "Editar Item",
            'links' =>  [
                'btnBack'   =>  [
                    'name'  =>  "<i class='fas fa-chevron-left'></i> Regresar al Listado",
                    'params'    =>  ['action' => 'index']
                ]
            ]
        ];
        $this->set("categorias", $categorias);
        $this->set("marcas", $marcas);
        $this->set("top_links", $top_acciones);
        $this->set("view_title", "Editar Producto");
        $this->set('producto', $producto);
        $this->set('tipo_monedas',$this->tipo_monedas);
        $this->set('_serialize', ['producto']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if ($this->deleteItem($id)){
            $this->Flash->success(__('El producto ha sido eliminado.'));
        }else{
            $this->Flash->error(__('Ha ocurrido un error, por favor, intente nuevamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }



    private function deleteItem($id){
        $producto = $this->Items->get($id);
        $code_qr_path = $producto->codigo_qr_ruta;
        $code_bar_path = $producto->codigo_barra_ruta;

        if ($this->Items->delete($producto)) {

            // eliminando los temporal de barra
            @unlink($this->ruta_img_codigos . $code_bar_path);
            @unlink($this->ruta_img_codigos . $code_qr_path);
            return true;
        } else {
            return false;
        }
    }




    public function saveCategoria($nombre, $descripcion = ''){
        $slug = $this->generarSlug($nombre);
        $cat = $this->ItemCategorias->find()->where(['slug' => $slug])->first();
        if (!$cat){
            $cat = $this->ItemCategorias->newEmptyEntity();
            $cat->nombre = $nombre;
            $cat->slug = $slug;
            $cat->descripcion = $descripcion;
            $cat = $this->ItemCategorias->Save($cat);
        }
        return $cat;
    }
    public function ajaxSaveCategoria(){
        //SE DEBE USAR LA MISMA FUNCION PORQUE SON PRACTICAMENTE LO MISMO...
        // SOLO RECIBEN LOS DATOS UNO COMO post Y este como GET
        $nombre = $this->request->getQuery('nombre');
        $descripcion = $this->request->getQuery('descripcion');

        $slug = $this->generarSlug($nombre);
        $cat = $this->ItemCategorias->find()->where(['slug' => $slug])->first();
        if (!$cat){
            $cat = $this->ItemCategorias->newEntity();
            $cat->nombre = $nombre;
            $cat->slug = $slug;
            $cat->descripcion = $descripcion;
            $cat = $this->ItemCategorias->Save($cat);
        }
        // return '$cat';
        exit;
    }


    public function saveProductoCategoriaRel($producto_id, $categoria_id){
        $rel = $this->ProductoCategorias->find()->where(['producto_id' => $producto_id, 'categoria_id' => $categoria_id])->first();
        if (!$rel){
            $rel = $this->ProductoCategorias->newEntity();
            $rel->producto_id = $producto_id;
            $rel->categoria_id = $categoria_id;
            $rel = $this->ProductoCategorias->save($rel);
        }
        return $rel;
    }

    public function saveItem($data){

        $permitir_duplicados = $this->getConfig("inv_codduplicados");

        $p = false;
        if ($permitir_duplicados == 0){
            // si no permite codigo duplicado, buscamos por codigo
            $p = $this->Items->find()->where([ 'codigo' => $data['codigo'] ])->first();
        }else{
            $data['id'] = isset($data['id']) ? $data['id'] : "";
            $p = $this->Items->find()->where([ 'id' => $data['id'] ])->first();
        }

        // si no existe el item, creamos uno nuevo
        if (!$p){
            $p = $this->Items->newEmptyEntity();
            $p->existe = "0";
        } else {
            $p->existe = "1";
        }



        // establecemos los valores
        $p = $this->Items->patchEntity($p, $data);

        $p = $this->Items->save($p);

        //$p->valor_unitario = floatval($this->regularizarValorUnitario($p->precio_venta,$p->inc_igv));

        // generamos las rutas de los temporal de barra y qr
        $p->codigo_barra_ruta = uniqid("bar") . ".png";
        $p->codigo_qr_ruta = uniqid("qr") . ".png";

        // establecemos las palabras de busqueda
        $p->info_busqueda = $this->generarInfoBusqueda($p);

        $p->codigo = ($p->codigo != '') ? $p->codigo : strtoupper(uniqid());

        //generamos los temporal de barra
        $this->generarCodigo($this->ruta_img_codigos . $p->codigo_barra_ruta, $p->codigo);
        $this->generarCodigo($this->ruta_img_codigos . $p->codigo_qr_ruta, $p->codigo, 'QR');


        // guardamos en la base de datos
        $this->Items->save($p);

        // $this->asociarProductoCategoria($p->id, $data['cat']);
        return $p;
    }

    /**
     * Genera la información de busqueda para los filtrados
     * @param type $p
     * @return type
     */
    private function generarInfoBusqueda($p){
        $b_marca= ($p->marca_id == null || $p->marca_id == "") ? "" : $p->marca_id ;
        $b_categoria= ($p->categoria_id == null || $p->categoria_id == "") ? "" : $p->categoria_id ;

        $marca = $this->ItemMarcas->find()->where(['id' => $b_marca])->first();
        $marca_nombre = ($marca) ? $marca->nombre : "";

        $categoria = $this->ItemCategorias->find()->where(['id' => $b_categoria])->first();
        $categoria_nombre = ($categoria) ? $categoria->nombre : "";

        // generamos el campo info busqueda, esto permite hacer búsquedas rápidas
        return "{$marca_nombre} - {$categoria_nombre} - {$p->codigo} - {$p->nombre}";
    }

    private function generarSlug($nombre){
        $nombre = trim($nombre);
        $slug = \Cake\Utility\Text::slug($nombre);
        $slug = strtolower($slug);
        return $slug;
    }

    private function actualizarInfoBusqueda(){

        $productos = $this->Items->find();
        foreach ($productos as $item){
            if($item->info_busqueda == '' || $item->info_busqueda == null ){
                $item->info_busqueda = $this->generarInfoBusqueda($item);
                $this->Items->save($item);

            }

        }
        $respuesta =
            [
              'success' =>true,
              'data'    =>  '',
              'message' =>  'todo bien'
            ];

        return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($respuesta));

    }


    /**
     *
     * @param type $filename
     * @param type $string
     * @param type $tipo_codigo BARRAS o QR
     */
    private function generarCodigo($filename, $string, $tipo_codigo = 'BARRAS'){
        $width_codbar_items = $this->getConfig("width_codbar_items");
        $height_codbar_items = $this->getConfig("height_codbar_items");

        $width_codbar_items = ($width_codbar_items == '' || $width_codbar_items == '0') ? 6 : $width_codbar_items ;
        $height_codbar_items = ($height_codbar_items == '' || $height_codbar_items == '0') ? 900 : $height_codbar_items ;

        if($tipo_codigo == 'BARRAS'){
            $generator = new Barcode\BarcodeGeneratorPNG();
            $data = $generator->getBarcode("$string", $generator::TYPE_CODE_128, intval($width_codbar_items) , intval($height_codbar_items));//MEDIDAS FUNCIONAN CORRECTAMENTE EN SGD 6,900
            file_put_contents("$filename", $data);
        }
        elseif ( $tipo_codigo == 'QR'){
            $qrCode = new QrCode("$string");
            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);
        }
    }

    private function limpiarCodigosConGuiones(){

        $items = $this->Items->find();
        foreach ($items as $item){
            $item->codigo = str_replace("-","",$item->codigo);
            $this->Items->save($item);
        }

        $respuesta = [
            'success'   =>  true,
            'data'      => $items,
            'message'   =>  'Todo bien'
        ];

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));

    }


    /********************************* Mantenimiento de Imagenes Galeria *********************************/

    public function galeria($item_id = ''){

        $item = $this->Items->get($item_id);
        $productoFotos = $this->ItemFotos->find()->where(['item_id' => $item_id]);
        //    print_r($productoFotos);
        //    exit();
        $productoFotos = $this->paginate($productoFotos, [ 'maxLimit' => 1]);
        foreach ($productoFotos as $pf) {
            $pf->default = $this->obtenerImgPredeterminda($pf->img_ruta,$pf->item_id);
            $pf->producto_id = $item->producto_id;
            $this->ItemFotos->save($pf);
        }

        // links que se muestra en la vista
        $top_acciones = [
            'btnBack'  =>  [
                'tipo'      =>     'link',
                'nombre'    =>     "<i class='fas fa-chevron-left fa-fw'></i>",
                'params'    =>     [
                    'action'     =>  'index',
                    $item_id
                ]

            ],
            'txtTitulo'  =>  [
                'tipo'      =>  'text',
                'nombre'    =>  "Galería de Fotos de {$item->nombre}"
            ],
            'btnNuevo'  =>  [
                'tipo'      =>     'link',
                'nombre'     =>  "<i class='fas fa-plus fa-fw'></i> Nueva Imagen",
                'params'     =>  [
                    'action'     =>  'GaleriaAdd',
                    $item_id
                ]

            ]
        ];

        $this->set('productoFotos',$productoFotos);
        $this->set('item',$item);
        $this->set("web_titulo", "Galería de Fotos");
        $this->set("top_acciones", $top_acciones);
    }

    private function obtenerImgPredeterminda($imagen,$prod_id){
        $resultado = "0";
        $producto = $this->Items->find()->where(['id' => $prod_id])->first();
        if(!(empty($imagen))) $resultado = ($imagen == $producto->imagen_ruta) ? "1" : "0";
        return $resultado;
    }

    public function galeriaAdd($producto_id = ''){

        $producto = $this->Items->get($producto_id);

        if ($this->request->is(['POST'])){
            $data = $this->request->getData();
            $foto = $data['foto'];
            if ($foto['size'] > 0){
                $producto = $this->Items->get($producto_id);

                $ruta_imagen = $producto_id  . "__" . str_replace(" ", "_", $foto['name']);
                @move_uploaded_file($foto['tmp_name'], $this->ruta_img_galeria . $ruta_imagen);

                $foto = $this->ItemFotos->newEntity();
                $foto->item_id = $producto->id;
                $foto->img_ruta = $ruta_imagen;
                // $foto->img_orden = $data['img_orden'];
                $foto->img_descripcion = $data['img_descripcion'];
                if ($this->ItemFotos->save($foto)){
                    $this->Flash->success("La foto se ha guardado con éxito");
                    return $this->redirect(['action' => 'galeria', $producto_id]);
                }
                $this->Flash->error("Ha ocurrido un error");
            }

        }

        // links que se muestra en la vista
        $top_acciones = [
            'btnBack'  =>  [
                'tipo'      =>     'link',
                'nombre'    =>     "<i class='fas fa-chevron-left fa-fw'></i>",
                'params'    =>     [
                    'controller' =>  'Mercaderia',
                    'action'     =>  'galeria',
                    $producto_id
                ]

            ],
            'txtTitulo'  =>  [
                'tipo'      =>  'text',
                'nombre'    =>  "Nueva Foto para {$producto->nombre}"
            ]
        ];

        $this->set("web_titulo", "Galería de Fotos");
        $this->set("top_acciones", $top_acciones);

    }

    public function imprimir($id = null){
        if ($this->request->is(['post', 'put','patch'])){
            $data = $this->request->getData();

            if(isset($data['desc_cod_barras']) && $data['desc_cod_barras'] == "1")
            {
                return $this->descargarPdfCodigos($id,'1');
            }
            else if(isset($data['desc_qr']) && $data['desc_qr'] == "1")
            {
                return $this->descargarPdfCodigos($id,'2');
            }

            $cant = $data['cantidad'];
            $size = $data['tamanio'];
            $tipo = $data['tipo'];
            $this->generarPdfCodigos($id,$cant,$size,$tipo);
            exit;
        }

        $item = $this->Items->get($id);

        $top_acciones = [];
        $top_acciones['title']  =  "Imprimir PDF con Códigos de Barras";

        $this->set("codigo", $id);
        $this->set("item", $item);
        $this->set("top_links", $top_acciones);
        $this->set("view_title", "Generar Códigos de Barras");

    }

    private function descargarPdfCodigos($item_id, $tipo){
        $producto = $this->Items->find()->where(['id' => $item_id])->first();
        $prod_nom ="";
        $ruta_temporal = $this->ruta_img_codigos . $item_id . ".png";
        if($tipo =="1"){
            $tipo = $producto->codigo_barra_ruta;
            $prod_nom = "codigo_de_barras";
            $this->generarCodigo($ruta_temporal, $producto->codigo);
        }else{
            $tipo = $producto->codigo_qr_ruta;
            $prod_nom = "codigo_qr";
            $this->generarCodigo($ruta_temporal,$producto->codigo,"QR");
        }

        $response = $this->response->withFile($ruta_temporal,['download'=>true , 'name'=> "{$prod_nom}_{$producto->id}.png"]);
        @unlink($ruta_temporal);
        return $response;
    }

    private function generarPdfCodigos($id,$cant,$size,$tipo) {
        $producto = $this->Items->find()->where(['id' => $id])->first();
        $prod_nom ="";
        $ruta_temporal = $this->ruta_img_codigos . $id . ".png";
        if($tipo =="1"){
            $tipo = $producto->codigo_barra_ruta;
            $prod_nom = "codigo_de_barras";
            $this->generarCodigo($ruta_temporal, $producto->codigo);
        }else{
            $tipo = $producto->codigo_qr_ruta;
            $prod_nom = "codigo_qr";
            $this->generarCodigo($ruta_temporal, $producto->codigo, "QR");
        }

        if($size =="1"){
            $html = $this->tamanioA4($cant, $ruta_temporal, $producto);
        }else{
            $html = $this->tamanioTicket($cant, $ruta_temporal, $producto);
        }

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setChroot(WWW_ROOT);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper("A4");
        $dompdf->render();

        $dompdf->stream(str_replace(" ","_",$producto->nombre) . "_listado_de_{$prod_nom}.pdf");

        @unlink($ruta_temporal);
    }


    private function tamanioA4($cant, $ruta_temporal, $p){
        $cont = 1;
        $html = "<table width='500' align='center' style='border-collapse: none;border-spacing: 0;'>";
        for ($i=0; $i < $cant; $i++) {
            if($cont==1){
                $html = $html."<tr align='center'>";
            }
            $html = $html."<td style='border: 1px solid gray;padding: 20px;'><img width='100' src='{$ruta_temporal}'>
            <div style='font-size:12px;'>".$p->codigo."</div><div style='font-size:12px;'>".$p->nombre."</div><td>";
            if($cont % 3 ==0){
                $html = $html."</tr>";
                $cont = 1;

            }else{
                $cont++;
            }
        }
        $html = $html."</table>";
        return $html;

    }

    private function tamanioTicket($cant, $ruta_temporal, $p){
        $html = "<table width='85' align='center' cellspacing='2''>";
        for ($i=0; $i < $cant; $i++) {
            $html = $html."<tr align='center'>";
            $html = $html."<td style='padding: 5px;'><img width='80' src='{$ruta_temporal}'><div style='font-size:12px;'>".$p->codigo."</div><div style='font-size:12px;'>".$p->nombre."</div><td>";
            $html = $html."</tr>";
        }
        $html = $html."</table>";
        return $html;
    }

    public function imagenPredeterminada(){
        $data = $this->request->getData();
        $imgProdId =  $data['imgProdId'];
        $imgProd = $this->ItemFotos->get($imgProdId, []);
        $prod = $this->Items->get($imgProd->item_id, []);
        $prod->imagen_ruta = $imgProd->img_ruta;
        $this->Items->save($prod);
        exit;
    }


    private function asociarProductoItem($producto_id, $item_array){
        $items = $this->Items->find()->where(['id IN'=>$item_array]);
        foreach ($items as $rel){
            $rel->producto_id = $producto_id;
            $rel = $this->Items->save($rel);
        }

    }



    /**
     * Cambia el estado de manera masiva de toda la seleccion de registros
     * se utiliza en
     * items
     * productos
     * categorias
     */
    public function accionesMasivasItems(){
        $data = $this->request->getData();
        $registros_id = explode(",", $data['ids']);
        $registros_id[] = 0;
        switch ($data['accion']){
            case "visibilidad":
                $items = $this->Items->find()->where([ 'id IN' => $registros_id ]);
                foreach ($items as $ii){
                    $ii->es_visible = $data['estado'];
                    $this->Items->save($ii);
                }
                break;
            case "eliminar":
                foreach ($registros_id as $ii){
                    $this->deleteItem($ii);
                }
                break;
            case "set-marca":
                $items = $this->Items->find()->where([ 'id IN' => $registros_id ]);
                foreach ($items as $ii){
//                    $ii->es_visible = $data['marca_id'];
//                    $this->Items->save($ii);
                }
                break;
            case "set-categoria":
                $items = $this->Items->find()->where([ 'id IN' => $registros_id ]);
                foreach ($items as $ii){
//                    $ii->es_visible = $data['categoria_id'];
//                    $this->Items->save($ii);
                }
                break;
            default;
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($data));
    }


    public function accionesMasivasCategorias(){
        $data = $this->request->getData();
        $registros_id = explode(",", $data['ids']);
        switch ($data['accion']){
            case "eliminar":
                $this->ItemCategorias->deleteAll(['id IN ' => $registros_id]);
                break;
            default;
        }
        exit;
    }
    public function accionesMasivasMarcas(){
        $data = $this->request->getData();
        $registros_id = explode(",", $data['ids']);
        switch ($data['accion']){
            case "eliminar":
                $this->ItemMarcas->deleteAll(['id IN ' => $registros_id]);
                break;
            default;
        }
        exit;
    }

    /*
     * Cambia el estado individual de cada item, de visible a no visible
     */
    public function cambiarEstado(){
        $data = $this->request->getData();
        switch ($data['entidad']){
            case 'item':
                $item = $this->Items->get($data['itemid']);
                $item->es_visible = $data['estado'];
                $this->Items->save($item);
                break;
            default: break;
        }

        echo json_encode([
            'estado'    =>  'ok',
            'data'      =>  'La visibilidad se ha cambiado correctamente'
        ]);
        exit;
    }




    // funciones temporales
    public function limpiarItems(){

        $this->Items->deleteAll(['id > '=>'0']);
        $this->Flash->success("Todos los items han sido eliminados");
        return $this->redirect(['action' => 'index']);


    }


    public function limpiarCategorias(){
        $this->ItemCategorias->deleteAll(['id > '=>'0']);
        $this->Flash->success("Todos las marcas han sido eliminados");
        return $this->redirect(['action' => 'categorias']);
    }

    public function exportar($items){
        //$data = $this->request->getData();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Productos");
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);

        $index = 2;


        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:J{$index}")->getFont()->setBold( true );
        $activeSheet->getColumnDimension('A')->setWidth(20);
        $activeSheet->getColumnDimension('B')->setWidth(30);
        $activeSheet->getColumnDimension('C')->setWidth(25);
        $activeSheet->getColumnDimension('D')->setWidth(30);
        $activeSheet->getColumnDimension('E')->setWidth(30);
        $activeSheet->getColumnDimension('F')->setWidth(15);


        $activeSheet->setCellValue("A{$index}", "Categoria");
        $activeSheet->setCellValue("B{$index}", "Código");
        $activeSheet->setCellValue("C{$index}", "Nombre");
        $activeSheet->setCellValue("D{$index}", "Ubicacion");
        $activeSheet->setCellValue("E{$index}", "Marca");
        $activeSheet->setCellValue("F{$index}", "P. Venta");

        $index ++;

        foreach ($items as $v){

            $cat = $this->ItemCategorias->find()->where(['id' => $v->categoria_id ?? "0"])->first();
            $mar = $this->ItemMarcas->find()->where(['id' => $v->marca_id ?? "0"])->first();

            $cat_nombre = ($cat) ? $cat->nombre : "Sin Categoria";
            $mar_nombre = ($mar) ? $mar->nombre : "Sin Marca";

            $activeSheet->setCellValue("A{$index}", $cat_nombre);
            $activeSheet->setCellValue("B{$index}", $v->codigo);
            $activeSheet->setCellValue("C{$index}", $v->nombre);
            $activeSheet->setCellValue("D{$index}", $v->ubicacion);
            $activeSheet->setCellValue("E{$index}", $mar_nombre);
            $activeSheet->setCellValue("F{$index}", $v->precio_venta);

            $index ++;
        }

        $filename = WWW_ROOT . "reporte_items_{$this->usuario_sesion['id']}.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $response = $this->response->withFile($filename,['download' => true,  'name' => "reporte_de_productos_" . date("Y_m_d") . ".xlsx"]);
        return $response;
    }


    public function servicios(){
        $opt_busqueda = $this->request->getQuery('opt_busqueda');
        $opt_categoria_id = $this->request->getQuery('opt_categoria_id');

        $servicios = $this->Items->find()
        ->where([
            'Items.info_busqueda LIKE ' => "%{$opt_busqueda}%",
            'Items.unidad' => 'ZZ'
        ])
        ->contain(['ItemCategorias']);
        if ($opt_categoria_id != ''){
            $servicios = $servicios->andWhere(['Items.categoria_id' => $opt_categoria_id]);
        }


        // links que se muestra en la vista
        $top_links = [
            'title'  =>  "Servicios",
            'links' =>  [
                'btnNuevo'  =>  [
                    'name'  =>  "<i class='fas fa-plus fa-fw'></i>Nuevo Servicio",
                    'params'    =>  [
                        'controller'    =>  'Items',
                        'action'        =>  'formServicio'
                    ]
                ]
            ]
        ];
        $categorias = $this->ItemCategorias->find('list');
        $servicios = $this->paginate($servicios, ['maxLimit' => 10]);
        $this->set("servicios", $servicios);

        $this->set("top_links", $top_links);
        $this->set("opt_busqueda", $opt_busqueda);
        $this->set("opt_categoria_id", $opt_categoria_id);
        $this->set("opt_categorias", $categorias);
        $this->set("view_title", "Lista de Servicios");


    }

    public function formServicio($id = ''){


        if ($id != ''){
            $servicio = $this->Items->find()->where(['id' => $id])->first();
        }else{
            $servicio=null;
        }


        if($this->request->is(['post','put','patch'])){
            $servicio = $this->Items->newEmptyEntity();
            $data = $this->request->getData();
            $prod = $data["productos"] ?? "" ;
            $canti = $data["cantidades"] ?? "";

            if ($data['codigo'] == "") {
                $data['codigo'] = uniqid();
            }

            if ($p = $this->saveItem($data)){
                if ($id != ""){
                    $this->asociarServicioProducto($id,$prod,$canti);
                }else{
                    $this->asociarServicioProducto($p->id, $prod, $canti);
                }

                $this->Flash->success("El servicio se ha guardado con éxito");

                return $this->redirect(['action' => 'servicios']);

            }else{
                return $this->redirect(['action' => 'formServicio']);
            }

        }




        $top_links = [
            'title'  =>  "Nuevo Servicio",
            'links' =>  [
                'btnBack'  =>  [
                    'name'  =>  "<i class='fas fa-chevron-left fa-fw'></i>Ver Servicios",
                    'params'    =>  [
                        'controller'    =>  'Items',
                        'action'        =>  'servicios'
                    ]
                ]
            ]
        ];

        $categorias = $this->ItemCategorias->find("list",[
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);
        $marcas = $this->ItemMarcas->find("list",[
            'keyField' => 'id',
            'valueField' => 'nombre'
        ]);

        $productos = $this->Items->find()->select([
            'id',
            'codigo',
            'nombre',
            'precio_venta'
        ]);


        $item_rel =  $this->ItemRels->find()->where(["item_id" => $id]);

        $this->set("servicio", $servicio);
        $this->set("categorias", $categorias);
        $this->set("marcas", $marcas);
        $this->set("productos", $productos);
        //$this->set("productos2", $productos2);
        $this->set("item_rel", $item_rel);
        $this->set("top_links", $top_links);
        $this->set("view_title", "Lista de Servicios");
    }

    public function guardarProducto(){
        $producto = $this->Items->newEmptyEntity();
        $producto->nombre = $this->request->getQuery("nombre");
        $producto->codigo = $this->request->getQuery("codigo_producto");
        $producto->precio_venta = $this->request->getQuery("precio_producto");
        $producto->descripcion = "";
        $this->Items->save($producto);
        echo json_encode($producto);
        exit;
    }

    private function asociarServicioProducto($servicio_id, $productos_id,$cantidad){

        // procedemos a eliminar las asociaciones existentes
        $this->ItemRels->deleteAll(['item_id' => $servicio_id]);
        if (is_array($productos_id)) {
            foreach ($productos_id as $prod => $on) {
                $rel = $this->ItemRels->newEmptyEntity();
                $rel->item_id = (int)$servicio_id;
                $rel->item2_id = (int)$prod;
                foreach($cantidad as $id=>$cant){
                    if ($id == $prod){
                        $rel->cantidad = $cant;
                    }
                }
                $this->ItemRels->save($rel);
            }
        }

        return $this->redirect(['action' => 'servicios']);

    }



    public function borradoMasivo(){
        $data = $this->request->getData();

        $this->Items->deleteAll(['id IN '=> $data['checks']]);

        $result = [];
        $result['status'] = 'ok';


        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    public function getOne($id = "") {
        try {
            $item = $this->Items->find()->where(['id' => $id])->first();
            $respuesta = [
                'success' => true,
                'data' => $item,
                'message' => 'Busqueda exitosa',
            ];
        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function save() {
        try {
            if ($this->request->is('POST')) {
                $data = $this->request->getData();
                $item = $this->Items->newEmptyEntity();
                $item = $this->Items->patchEntity($item, $data);
//                $item->id = $data['id'];

                if ($this->request->getData('id', '') != "") {
                    $item->id = $data['id'];
                }
                if(isset($data['item_tipo_moneda']))
                {
                    $item->tipo_moneda = $data['item_tipo_moneda'];
                }



                $item->codigo = $data['codigo'] == "" ? uniqid() : $data['codigo'];

                $item->info_busqueda = $this->generarInfoBusqueda($item);

                $item = $this->Items->save($item);
                $item->img_ruta = $this->saveUploadedFile($data['file_img_ruta'], "producto_" . $item->id, "media/items");

                $item = $this->Items->save($item);

                $respuesta = [
                    'success' => true,
                    'data' => $item,
                    'message' => 'Guardado exitoso',
                ];
            } else {
                $respuesta = [
                    'success' => false,
                    'data' => '',
                    'message' => 'No se recibieron datos',
                ];
            }
        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function getMarcas() {
        try {
            $items = $this->ItemMarcas->find();
            $respuesta = [
                'success' => true,
                'data' => $items,
                'message' => 'Busqueda exitosa',
            ];
        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function getCategorias() {
        try {
            $items = $this->ItemCategorias->find();
            $respuesta = [
                'success' => true,
                'data' => $items,
                'message' => 'Busqueda exitosa',
            ];
        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function getServiciosPorInsumo($insumo_id) {
        try {
            $rels = $this->ItemRels->find()->where([
                'item2_id' => $insumo_id
            ])->contain(['Items']);

            $respuesta = [
                'success' => true,
                'data' => $rels,
                'message' => 'Busqueda Exitosa',
            ];
        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function getAll() {
        $all = $this->Items->find();
        $opt_nombre = $this->request->getQuery('opt_nombre','');
        if( isset($opt_nombre) && $opt_nombre != ''){
            $all = $all->andWhere(['nombre LIKE' => "%{$opt_nombre}%"]);
        }
        $respuesta = [
            'success' => true,
            'data' => $all,
            'message' => 'Busqueda exitosa'
        ];

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function getForService($servicio_id) {
        try {
            $rels = $this->ItemRels->find()->where([
                'item_id' => $servicio_id
            ]);

            $ids = [0];

            foreach ($rels as $r) {
                $ids[] = $r->item2_id;
            }

            $items = $this->Items->find()->where(['id IN' => $ids]);

            $respuesta = [
                'success' => true,
                'data' => $items,
                'message' => 'Busqueda Exitosa',
            ];
        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }
    public function kardex($item_id) {
        $item = $this->Items
            ->find()
            ->where(['Items.id' => $item_id])
            ->contain(['Stock'=> function($query) use($item_id){
                return $query->select([
                   'stock_global' => $query->func()->sum('Stock.stock')
                ]);
            } ])
            ->first();

        $stockHistorial = $this->StockHistorial->find()->where([
            'item_id' => $item_id,
        ])->contain(['Usuarios'])->order(['StockHistorial.id' => 'DESC']);

        $stockHistorial = $this->paginate($stockHistorial, ['maxLimit' => 10]);
        $this->set(compact('stockHistorial'));

        $top_links = [
            'title' => 'Kardex de ' . $item->nombre,
            'links' => [
            ]
        ];

        $this->set(compact('item'));
        $this->set('view_title', 'Kardex de ' . $item->nombre);
        $this->set(compact('top_links'));
    }
    public function kardexExcel($item, $kardex ){
        //$data = $this->request->getData();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Kardex - {$item->nombre}" );
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);
        $index = 2;
        // imprimimos los parámetros del reporte

        $styleArrayAnulado = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];

        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];


        $activeSheet->setCellValue("A{$index}", $item->nombre );
        $activeSheet->setCellValue("B{$index}", $item->descripcion );

        $index++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:L{$index}")->getFont()->setBold(true);
        $activeSheet->getColumnDimension('A')->setWidth(20);
        $activeSheet->getColumnDimension('B')->setWidth(30);
        $activeSheet->getColumnDimension('C')->setWidth(20);
        $activeSheet->getColumnDimension('D')->setWidth(30);
        $activeSheet->getColumnDimension('E')->setWidth(8);
        $activeSheet->getColumnDimension('F')->setWidth(30);



        $activeSheet->setCellValue("A{$index}", "Usuario");
        $activeSheet->setCellValue("B{$index}", "-");
        $activeSheet->setCellValue("C{$index}", "Operacion");
        $activeSheet->setCellValue("D{$index}", "Comentario");
        $activeSheet->setCellValue("E{$index}", "Cantidad");
        $activeSheet->setCellValue("F{$index}", "F. Creación");
        $index++;  // incrmentamos en 1 fila

        foreach($kardex as $kx){
            if($kx->usuario){
                $activeSheet->setCellValue("A{$index}", $kx->usuario->nombre);
            }
            $activeSheet->setCellValue("B{$index}", "-");
            $activeSheet->setCellValue("C{$index}", $kx->operacion);
            $activeSheet->setCellValue("D{$index}", $kx->comentario);
            $activeSheet->setCellValue("E{$index}", $kx->cantidad);
            $activeSheet->setCellValue("F{$index}", ($kx->created != null && $kx->created != '') ?$kx->created->format("d-m-Y H:i:s") :'' );
            $index++;  // incrmentamos en 1 fila
        }

        $filename = WWW_ROOT . "reporte_kardex.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $filename_to_download = "reporte_kardex_{$item->nombre}.xlsx";

        $response = $this->response->withFile($filename, ['download' => true, 'name' => $filename_to_download]);
        return $response;
    }
    public function kardexPdf($item, $kardex){
        ini_set('memory_limit', '-1');

        $this->viewBuilder()->setLayout("none");
        $this->viewBuilder()->setVars([
            'kardex'     => $kardex,
            'item'     => $item,
        ]);

        $html = $this->render("exportarKardexPdf");

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();
        $options->set('isRemoteEnabled',true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4");
        $dompdf->render();
        $dompdf->stream("Reporte_kardex_{$item->nombre}.pdf", ['Attachment' => true]);
    }

    /**
     *@author Dylan
     *
     */
    public function ajaxFilterItems(){
        $q = $this->request->getQuery("query");
        $resp = [];
        $items =  $this->Items->find()->where(['info_busqueda LIKE' => "%{$q}%"]);
        foreach ($items as $i){
            $resp[] = [
                'id' => $i['id'],
                'value' => $i['nombre'],
            ];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                "query" =>  $q,
                "suggestions"   =>  $resp
            ]));
    }

    /**
     * @author Ali Reyes
     */
    public function ajaxAutocompleteServices()
    {
        $q = $this->request->getQuery("query", '');
        // filtramos por organización
        $items = $this->Items->find("all")
                            ->where(
                                [
                                    'unidad' => 'ZZ',
                                    'info_busqueda LIKE' => "%{$q}%"
                                ]
                            );

        foreach ($items as $c) {
            $c['value'] = $c['nombre'];
        }
        $respuesta = json_encode([
            "query" => $q,
            "suggestions" => $items
        ]);

        echo $respuesta;
        exit;
    }

    public function getAllServices()
    {
        $respuesta =
        [
            'success'   =>  false,
            'data'      =>  '',
            'message'   =>  'Sin datos'
        ];

        $servicios = $this->Items->find()->where(['unidad'=>'ZZ']);

        if($servicios && $servicios != null)
        {
            $respuesta =
                [
                    'success'   =>  true,
                    'data'      =>  $servicios,
                    'message'   =>  'La consulta ha sido exitosa'
                ];
        }

        return  $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode($respuesta));

    }

    public function getProductosMasVendidos($fecha_ini,$fecha_fin){

        $ventas =$this->Ventas->find()
        ->where(
            [
                'fecha_venta >='    =>  $fecha_ini .    " 00:00:00",
                'fecha_venta <='    =>  $fecha_fin  .   " 23:59:59"
            ]);

        $arrayIdsVentas =  [ 0 ];
        foreach($ventas as $venta){
            $arrayIdsVentas[]   =   $venta->id;
        }

        $ventaRegistros = $this->VentaRegistros
        ->find()
        ->where(['VentaRegistros.venta_id IN' =>    $arrayIdsVentas,
                	'Items.id   IS NOT' =>  null
        ])
        ->contain(['Items']);

        $ventaRegistros = $ventaRegistros
        ->select([
            'item_nombre'   =>  'Items.nombre',
            'cantidad_vendida'  =>  $ventaRegistros->func()->sum('VentaRegistros.cantidad')
        ])
        ->order(['cantidad_vendida' => 'DESC'])
        ->group(['Items.id'])
        ->limit(20);

        $respuesta =
        [
            'success'   =>  true,
            'data'      =>  $ventaRegistros,
            'message'   =>  'Consulta satisfactoria'
        ];

        return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode($respuesta));
    }


}
