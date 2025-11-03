<?php
declare(strict_types=1);

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Stock Controller
 *
 * @property \App\Model\Table\StockTable $Stock
 * @method \App\Model\Entity\Stock[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StockController extends AppController
{

    protected $Items = null;
    protected $StockHistorial = null;
    protected $ParteSalidas = null;
    protected $ParteEntradas = null;
    protected $ParteEntradaRegistros = null;
    protected $ParteSalidaRegistros = null;
    public function initialize(): void {
        parent::initialize();
        $this->Items = $this->fetchTable("Items");
        $this->StockHistorial = $this->fetchTable("StockHistorial");
        $this->ParteSalidas = $this->fetchTable("ParteSalidas");
        $this->ParteEntradas = $this->fetchTable("ParteEntradas");
        $this->ParteEntradaRegistros = $this->fetchTable("ParteEntradaRegistros");
        $this->ParteSalidaRegistros = $this->fetchTable("ParteSalidaRegistros");

        $this->loadComponent("StockManager", ['usuario'=>$this->usuario_sesion]);
    }
    public function beforeRender(\Cake\Event\EventInterface $event) {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }

    public function ingresarMercaAlmacen($almacen_id){
        $almacen = null;
        $almacenesObjs = $this->Almacenes->find("all");
        $almacenes = [];
        foreach ($almacenesObjs as $a){
            if($a->id == $almacen_id){
                $almacen = $a;
            }else{
                $almacenes[$a->id] = $a->nombre;
            }
        }

        if($this->request->is('POST')){
            $data = $this->request->getData();
            $almacen_origen_id = $data['almacen_origen_id'];
            $almacen_destino_id = $data['almacen_destino_id'];
            foreach ($data['p'] as $item){
                if($item['id'] != ''){
                    $this->setStock($item['id'], $almacen_destino_id, $item['cantidad']);
                    $this->setStock($item['id'], $almacen_origen_id, $item['cantidad'] * -1);
                }
            }
            $this->Flash->success("Los stocks se han actualizado con éxito en $almacen->nombre");
            return $this->redirect(['controller' => 'inventario', 'action' => 'verStocks']);
        }

        $top_acciones = [
            'btnBack'  =>  [
                'tipo'      =>     'link',
                'nombre'    =>     "<i class='fas fa-chevron-left fa-fw'></i>",
                'params'    =>     [
                    'controller' =>  'Inventario',
                    'action'     =>  'verStocks'
                ]

            ],
            'txtTitulo'  =>  [
                'tipo'      =>  'text',
                'nombre'    =>  "Ingreso de Productos en Lazzat Store {$almacen->nombre}"
            ]
        ];

        $this->set("web_titulo", "Ingresar de Mercadería en $almacen->nombre ");
        $this->set("almacen", $almacen);
        $this->set("almacenes", $almacenes);
        $this->set("top_acciones", $top_acciones);
    }

    public function index() {
        $aid = $this->request->getQuery('almacen_id', '');
        if ($aid == "0" || $aid == "") {
            $almacen = $this->Almacenes->find()->first();
            $aid = $almacen->id;
        }
        $this->set('almacen_id', $aid);

        $item_nombre = $this->request->getQuery('item_nombre', '');
        $stock_min = $this->request->getQuery('stock_min', '');
        $this->set(compact('item_nombre','stock_min'));

        $almacen = $this->Almacenes->get($aid);
        $productos = $this->Items->find()->where(['unidad <>' => 'ZZ'])->contain(['ItemMarcas']);
        
        if ($stock_min == "1") {
            $productos->leftJoinWith('Stock')
            ->distinct(['Items.id'])
            ->where(function ($exp, $q) {
                return $exp->lt('Stock.stock', $q->newExpr('Items.stock_minimo_local'));
            } , ['almacen_id' => $aid]);
        }        

        if ($item_nombre != "") {
            $productos = $productos->andWhere(
                [
                    'OR'    => 
                    [
                        'Items.nombre LIKE ' => "%$item_nombre%",
                        'Items.descripcion_alternativa LIKE'  =>  "%$item_nombre%"
                    ] 
                ]);
        }        

        // $productos = $productos->matching('Stock', function ($q) use($aid) {
        //     return $q->where(['Stock.stock >=' => 1, 'almacen_id' => $aid]);
        // });

        if($this->request->getQuery('btn_exportar') == '1'){
            return $this->exportarStockExcel($almacen->id,$item_nombre);
        }
        $this->paginate = [
            'limit' => 10,
        ];
        $productos = $this->paginate($productos);

        foreach ($productos as $p) {
            $s = $this->Stock->find()->where([
                'almacen_id' => $aid,
                'item_id' => $p->id,
            ])->first();

            if (!$s) {
                $s = $this->Stock->newEmptyEntity();
                $s->almacen_id = $aid;
                $s->item_id = $p->id;
                $s->stock = 0;

                $s = $this->Stock->save($s);
            }

            $p->stock = $s->stock;
            $p->stock_id = $s->id;
        }

        $top_links = [];
        $top_links['links'] = [];
        $top_links['title'] = "Stock de {$almacen->nombre}";
            $top_links['links']['btnAlmacenes'] = [
                'name' => '<i class="fa fa-fw fa-database"></i> Almacenes',
                'params' => [
                    'controller' => 'Almacenes',
                    'action' => 'index',
                ]
            ];
            $top_links['links']['btnMover'] = [
                'name' => '<i class="fa fa-fw fa-truck"></i> Mover Mercaderia',
                'params' => [
                    'action' => 'moverMercaderia'
                ],
            ];

        $top_links['links']['btnComparar'] = [
            'name' => '<i class="fa fa-fw fa-greater-than-equal"></i> Comparar Mercaderia',
            'params' => [
                'action' => 'comparacion'
            ],
        ];


        $almacenes = $this->Almacenes->find('list');
        $this->set(compact('almacenes'));

        $this->set("view_title", "Stock de Almacen");
        $this->set("top_links", $top_links);

        $this->set("titulo", "Detalles del Almacen $almacen->nombre");
        $this->set("productos", $productos);
        $this->set("almacen", $almacen);
    }

    public function comparacion() {
        $exportar = $this->request->getQuery('exportar', '');
        $aid = $this->request->getQuery('almacen_id', '');
        if ($aid == "0" || $aid == "") {
            $almacen = $this->Almacenes->find()->first();
            $aid = $almacen->id;
        }
        $this->set('almacen_id', $aid);

        $item_nombre = $this->request->getQuery('item_nombre', '');
        $this->set(compact('item_nombre'));

        $almacen = $this->Almacenes->get($aid);
        $productos = $this->Items->find()->where(['unidad <>' => 'ZZ'])->contain(['ItemMarcas']);

        if ($item_nombre != "") {
            $productos = $productos->andWhere(
                [
                    'OR'    =>
                    [
                        'Items.nombre LIKE ' => "%$item_nombre%",
                        'Items.descripcion_alternativa LIKE'  =>  "%$item_nombre%"
                    ]
                ]);
        }

        if ($exportar == "1") {
            return $this->exportarComparacion($productos);
        }

        $this->paginate = [
            'limit' => 10,
        ];
        $productos = $this->paginate($productos);

        $alms = $this->Almacenes->find();
        $this->set(compact('alms'));

        foreach ($productos as $p) {

            $i = 0;
            $arr_stock = [];
            foreach ($alms as $alm) {
                $s = $this->Stock->find()->where([
                    'almacen_id' => $alm->id,
                    'item_id' => $p->id,
                ])->first();

                if (!$s) {
                    $s = $this->Stock->newEmptyEntity();
                    $s->almacen_id = $alm->id;
                    $s->item_id = $p->id;
                    $s->stock = 0;

                    $s = $this->Stock->save($s);
                }

                $arr_stock["$s->id"] = $s->stock;

                $i++;
            }

            $p->arr_stock = $arr_stock;
        }

        $top_links = [];
        $top_links['links'] = [];
        $top_links['title'] = "Comparar Stock en Almacenes";
            $top_links['links']['btnMover'] = [
                'name' => '<i class="fa fa-fw fa-truck"></i> Mover Mercaderia',
                'params' => [
                    'action' => 'moverMercaderia'
                ],
            ];

        $almacenes = $this->Almacenes->find('list');
        $this->set(compact('almacenes'));

        $this->set("view_title", "Stock de Almacen");
        $this->set("top_links", $top_links);

        $this->set("titulo", "Detalles del Almacen $almacen->nombre");
        $this->set("productos", $productos);
        $this->set("almacen", $almacen);
    }

    public function exportarComparacion($productos) {
        $alms = $this->Almacenes->find();
        foreach ($productos as $p) {

            $i = 0;
            $arr_stock = [];
            foreach ($alms as $alm) {
                $s = $this->Stock->find()->where([
                    'almacen_id' => $alm->id,
                    'item_id' => $p->id,
                ])->first();

                if (!$s) {
                    $s = $this->Stock->newEmptyEntity();
                    $s->almacen_id = $alm->id;
                    $s->item_id = $p->id;
                    $s->stock = 0;

                    $s = $this->Stock->save($s);
                }

                $arr_stock["$s->id"] = $s->stock;

                $i++;
            }

            $p->arr_stock = $arr_stock;
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Venta de Productos");
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);

        $index = 2;
        $activeSheet->setCellValue("A{$index}", "STCOCKS TOTALES POR ALMACEN");
        $index += 2;
        $count_alm = $alms->count();

        $activeSheet->getStyle("A{$index}:Z{$index}")->getFont()->setBold( true );

        $activeSheet->setCellValue("A{$index}", "Nro");
        $activeSheet->setCellValue("B{$index}", "Codigo");
        $activeSheet->setCellValue("C{$index}", "Marca");
        $activeSheet->setCellValue("D{$index}", "Nombre");
        $activeSheet->setCellValue("E{$index}", "Unidad");
        $activeSheet->setCellValue("F{$index}", "Descripcion");
        $activeSheet->setCellValue("G{$index}", "P. Compra");
        $activeSheet->setCellValue("H{$index}", "P. Venta");
        $activeSheet->setCellValue("I{$index}", "Inc. IGV");

        $i = 74;
        foreach ($alms as $alm) {
            $activeSheet->setCellValue("" . chr($i) . "{$index}", $alm->nombre);
            $i++;
        }

        $index++;

        foreach ($productos as $p) {
            $activeSheet->setCellValue("A{$index}", $p->id);
            $activeSheet->setCellValue("B{$index}", $p->codigo);
            $activeSheet->setCellValue("C{$index}", $p->Marca ? $p->Marca->nombre : "");
            $activeSheet->setCellValue("D{$index}", $p->nombre);
            $activeSheet->setCellValue("E{$index}", $p->unidad);
            $activeSheet->setCellValue("F{$index}", $p->descripcion);
            $activeSheet->setCellValue("G{$index}", $p->precio_compra);
            $activeSheet->setCellValue("H{$index}", $p->precio_venta);
            $activeSheet->setCellValue("I{$index}", $p->inc_igv);

            $i = 74;
            foreach ($p->arr_stock as $ak => $av) {
                $activeSheet->setCellValue("" . chr($i) . "{$index}", $av);
                $i++;
            }

            $index++;
        }

        $filename = WWW_ROOT . "reporte_comparacion_" . date('d_m_Y') . ".xlsx";

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        return $this->response->withFile($filename,['download' => true,  'name' => "reporte_comparacion_" . date('d_m_Y') . ".xlsx"]);


    }

    public function detalleAlmacen($aid){

        $almacen = $this->Almacenes->get($aid);
        $productos = $this->Items->find()->contain(['ItemMarcas']);

        $this->paginate = [
            'limit' => 10,
        ];
        $productos = $this->paginate($productos);

        foreach ($productos as $p) {
            $s = $this->Stock->find()->where([
                'almacen_id' => $aid,
                'item_id' => $p->id,
            ])->first();

            if (!$s) {
                $s = $this->Stock->newEmptyEntity();
                $s->almacen_id = $aid;
                $s->item_id = $p->id;
                $s->stock = 0;

                $s = $this->Stock->save($s);
            }

            $p->stock = $s->stock;
            $p->stock_id = $s->id;
        }

        $top_links = [
            'title'    =>  "Stock de {$almacen->nombre}",
            'links' => [
                'btnAtras' => [
                    'name' => '<i class="fa fa-fw fa-chevron-left"></i> Atrás',
                    'params' => [
                        'controller' => 'Almacenes',
                        'action' => 'index',
                    ]
                ],
            ],
        ];

        $this->set("view_title", "Stock de Almacen");
        $this->set("top_links", $top_links);

        $this->set("titulo", "Detalles del Almacen $almacen->nombre");
        $this->set("productos", $productos);
        $this->set("almacen", $almacen);
    }

    public function editarStock($stockid){
        $stock = $this->Stock->find("all")->contain(['Items','Almacenes'])->where(['Stock.id' => $stockid])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $stock->stock = $data['stock'];
            $this->Stock->save($stock);
            return $this->redirect(['action' => 'detalleAlmacen' , $stock->almacen_id]);
        }
        $this->set("title", "Actualizar Stock");
        $this->set("stock", $stock);
    }

    public function editarProducto($stockid){
        $stock = $this->Stock->find("all")->contain(['Productos','Almacenes'])->where(['Stock.id' => $stockid])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $p = $this->Productos->get($stock->producto_id);
            $p->codigo = $data['codigo'];
            $p->nombre = $data['nombre'];
            $p->precio_compra = $data['precio_compra'];
            $p->precio_venta = $data['precio_venta'];
            $p->precio_venta_minimo = $data['precio_venta_minimo'];
            $p->precio_venta_por_mayor = $data['precio_venta_por_mayor'];
            $this->Productos->save($p);
            return $this->redirect(['action' => 'detalleAlmacen' , $stock->almacen_id]);
        }
        $this->set("title", "Actualizar Stock");
        $this->set("stock", $stock);
        $this->set("producto", $stock->producto);
    }

    public function clearStockByItemID($id){
        $this->Stock->deleteAll(['item_id' => $id]);
    }

    public function setStock($item_id, $almacen_id, $stock){
        $st = $this->Stock->find("all")->where(['almacen_id' => $almacen_id])->andWhere(['item_id' => $item_id])->first();
        if(!$st){
            $st = $this->Stock->newEmptyEntity();
            $st->almacen_id = $almacen_id;
            $st->item_id = $item_id;
            $st->stock = $stock;
        } else {
            $st->stock += $stock;
        }

        $this->Stock->save($st);

        return $st;
    }

    public function establecerStock($item_id, $almacen_id, $stock){
        $st = $this->Stock->find("all")->where(['almacen_id' => $almacen_id])->andWhere(['item_id' => $item_id])->first();

        if(!$st){
            $st = $this->Stock->newEmptyEntity();
            $st->almacen_id = $almacen_id;
            $st->item_id = $item_id;
            $oldStock = 0;
        } else {
            $oldStock = $st->stock;
        }

        $st->stock = $stock;

        $this->Stock->save($st);

        return $oldStock;
    }



    public function getAllStock(){

        $item_id = $this->request->getQuery('item_id');
        $respuesta=[
            'success' =>false,
            'data'=> '',
            'message'=>'No se han encontrado datos'
        ];

        $almacenes = $this->Almacenes->find();
        $stocks = $this->Stock->find()->where(['item_id'=>$item_id]);
        if($stocks){
            $almacenes_stock = [];
            foreach($stocks as $stock){
                $almacenes_stock[] = $stock->almacen_id;
            }
            foreach ( $almacenes as $almacen ){
                if(!(in_array($almacen->id,$almacenes_stock))){
                    $nuevo_stock = $this->Stock->newEmptyEntity();
                    $nuevo_stock->almacen_id = $almacen->id;
                    $nuevo_stock->item_id = $item_id;
                    $nuevo_stock->stock = 0;
                    $this->Stock->save($nuevo_stock);
                }
            }
            $stocksAlmacenes = $this->Stock->find()
                ->contain(['Almacenes'])
                ->where(['item_id'=>$item_id]);
            $respuesta=[
              'success' =>true,
              'data'=> $stocksAlmacenes,
                'message'=>'Busqueda exitosa'
            ];
        }
        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    //Nuevo
    public function stockJson(){

        $items = $this->request->getQuery("items");
        $almacen_sal = $this->request->getQuery("aid_salida");
        $almacen_ent = $this->request->getQuery("aid_entrada");
        $stock_total = [];

        foreach(json_decode($items,true) as $item_id){

            $stock_origen = $this->StockManager->getStock($almacen_sal, $item_id);
            $stock_destino = $this->StockManager->getStock($almacen_ent, $item_id);

            $stock_total[] = ['stock_origen'=> $stock_origen, 'stock_destino'=>$stock_destino,'item_id'=>$item_id ];
        }

        $respuesta = [
            'data' => $stock_total
        ];

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }



    public function actualizarStock() {
        $respuesta = [
            'success' => false,
            'data' => '',
            'message' => 'No se recibieron datos'
        ];
        $data = $this->request->getData();

        try {
            if ($this->request->is('POST')) {
                $data = $this->request->getData();
                $s = $this->Stock->find()->where([
                    'id' => $data['stock_id'],
                ])->first();
                if(!(intval($s->stock)=== intval($data['stock']))){
                    $old_stock = $s->stock;
                    $s->stock = $data['stock'];
                    $s = $this->Stock->save($s);

                    // generar kardex
                    $item = $this->Items->find()->where(['id' => $s->item_id])->first();

                    $sh = $this->StockHistorial->newEmptyEntity();
                    $sh->item_id = $s->item_id;
                    $sh->item_codigo = $item->codigo;
                    $sh->item_nombre = $item->nombre;
                    $sh->usuario_id = $this->usuario_sesion['id'];
                    $sh->almacen_id = $s->almacen_id;
                    $sh->operacion = "MODIFICACION MANUAL";
                    $sh->cantidad = intval($s->stock) - intval($old_stock);
                    if(intval($s->stock) >intval($old_stock)){
                        $sh->comentario = "Ingreso de {$sh->cantidad} Items";
                    }elseif (intval($s->stock) <intval($old_stock)){
                        $sh->comentario = "Retiro de ".abs($sh->cantidad)." Items";
                    }


                    $sh->fecha = date('Y-m-d H:i:s');
                    $sh = $this->StockHistorial->save($sh);

                    $respuesta = [
                        'success' => true,
                        'data' => [$s, $data],
                        'message' => 'Operacion exitosa',
                    ];
                }else{
                    $respuesta = [
                        'success' => true,
                        'data' => json_encode([$s->stock,$data['stock']]),
                        'message' => 'No se han efectuado cambios,los stocks eran iguales',
                    ];
                }
            }
        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => $data,
                'message' => 'ERROR: ' . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }


    public function getStockItem($stock_id){
        $respuesta =    [
            'success'   =>  false,
            'data'      =>  '',
            'message'   =>  'No se ha encontrado el stock'
        ];


        $st = $this->Stock->find()
            ->where(['Stock.id' => $stock_id])
            ->contain(['Items'])
            ->first();

        if($st){
            $respuesta =
                [
                    'success'    => true,
                    'data'      =>  $st,
                    'message'    => 'Busqueda Exitosa'
                ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($respuesta));

    }



    public function moverMercaderia() {

        if($this->request->is('POST')){
            $data = $this->request->getData();
            $aid_salida = $data['almacen_salida_id'];
            $aid_entrada = $data['almacen_entrada_id'];

            if( isset($data['guardar']) && $data['guardar'] === '1' ){
                $items_json = json_decode($data['items_json'],true);
                if($this->actualizarStockMovimientoMercaderia($aid_salida,$aid_entrada,$items_json)){
                    $this->Flash->success('Stock movido satisfactoriamente');
                }else{
                    $this->Flash->error('Ha habido un error en el movimiento de la mercaderia');

                }
                $this->redirect(['action' => 'moverMercaderia']);

            }
            $this->set('almacen_salida',$aid_salida);
            $this->set('almacen_entrada',$aid_entrada);
        }

        //Filtro por nombre
        $val = $this->request->getData("nombre");
        $this->set('nombre', $val);

        $items = $this->Items->find();

        if ($val != "") {
            $items = $items->andWhere([
                'info_busqueda LIKE' => "%$val%",
            ]);
        }
        $this->paginate = [
            'limit' => 10,
        ];
        $items = $this->paginate($items);
        $this->set(compact('items'));

        $top_links = [
            'title' => 'Movimiento de mercaderia'
        ];

        /// Cargar selects
        $almacenes = $this->Almacenes->find('list');
        $this->set(compact('almacenes'));


        $this->set(compact('top_links'));
        $this->set('view_title', 'Mover Mercadería');
    }

    public function actualizarStockMovimientoMercaderia($aid_salida,$aid_entrada,$items){

        try{
            $almacen_salida = $this->Almacenes->find()->where(['id'=>$aid_salida])->first();
            $almacen_entrada = $this->Almacenes->find()->where(['id'=>$aid_entrada])->first();
            $ParteSalida = new ParteSalidasController();
            $parte_salida = $ParteSalida->saveSalida([
                'almacen_salida_id' => ($almacen_salida) ? $almacen_salida->id : 0,
                'almacen_destino_id' => ($almacen_entrada) ? $almacen_entrada->id : 0,
                'usuario_id' => $this->usuario_sesion['id'],
                'descripcion' => "Desde mover mercaderia",
                'fecha_emision' => date("Y-m-d")
            ]);

            $ParteEntrada = new ParteEntradasController();
            $parte_entrada = $ParteEntrada->saveEntrada([
                'parte_salida_id' => $parte_salida ? $parte_salida->id : 0,
                'almacen_id' => ($almacen_entrada) ? $almacen_entrada->id : 0,
                'usuario_id' => $this->usuario_sesion['id'],
                'descripcion' => "Desde mover mercaderia",
                'fecha' => date("Y-m-d"),
            ]);
            
            foreach ($items as $k => $_item){

                $item = $this->Items->find()->where(['id' => $_item['item_id']])->first();

                // salida
                //                $allData=[];
                $this->StockManager->retirarStock(
                    $item->id,
                    $aid_salida,
                    $_item['stock_mover'],
                    "MOVIMIENTO",
                    "SALIDA DE MERCADERIA"
                );
                $ParteEntradaRegistros = new ParteEntradaRegistrosController();
                $parte_entrada_registro = $ParteEntradaRegistros->saveRegistro([
                    'parte_entrada_id' => $parte_entrada->id,
                    'item_id' => $item->id,
                    'item_index' => $k+1,
                    'cantidad' => $_item['stock_mover'],
                ]);


                // llegada
                $this->StockManager->ingresarStock(
                    $item->id,
                    $aid_entrada,
                    $_item['stock_mover'],
                    "MOVIMIENTO",
                    "ENTRADA DE MERCADERIA"
                );
                $ParteSalidaRegistros = new ParteSalidaRegistrosController();
                $parte_salida_registro = $ParteSalidaRegistros->saveRegistro([
                    'parte_salida_id' => $parte_salida->id,
                    'item_id' => $item->id,
                    'item_index' => $k+1,
                    'cantidad' => $_item['stock_mover'],
                ]);

                //                array_push($allData);
            }

            return true;
        }catch(\Throwable $e){
            return false;
        }



    }
    public function exportarStockExcel($id, $filtro = ''){
        $almacen = $this->Almacenes->find()->where(['id' => (int)$id ])->first();
        $stocks = $this->Stock->find()->where(['Stock.almacen_id' => (int)$id , 'Items.nombre LIKE' => "%$filtro%" ])->contain(['Items'])->orderAsc('Items.nombre');

        //$data = $this->request->getData();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Stock Almacen - {$almacen->nombre}" );
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


        $activeSheet->getStyle("A{$index}")->getFont()->setBold(true);
        $activeSheet->setCellValue("A{$index}", $almacen->nombre );
        $activeSheet->setCellValue("C{$index}", $almacen->direccion );

        $index++;  // incrmentamos en 1 fila
        $index++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:L{$index}")->getFont()->setBold(true);
        $activeSheet->getColumnDimension('A')->setWidth(35);
        $activeSheet->getColumnDimension('B')->setWidth(8);
        $activeSheet->getColumnDimension('C')->setWidth(35);
        $activeSheet->getColumnDimension('D')->setWidth(15);
        $activeSheet->getColumnDimension('E')->setWidth(15);
        $activeSheet->getColumnDimension('F')->setWidth(15);
        // $activeSheet->getStyle('A6:X6')->applyFromArray($styleArrayTitulo);

        $activeSheet->setCellValue("A{$index}", "Nombre");
        $activeSheet->setCellValue("B{$index}", "Unidad");
        $activeSheet->setCellValue("C{$index}", "Descripcion");
        $activeSheet->setCellValue("D{$index}", "Precio Compra");
        $activeSheet->setCellValue("E{$index}", "Precio Venta");
        $activeSheet->setCellValue("F{$index}", "Stock");
        $index++;  // incrmentamos en 1 fila

        foreach($stocks as $ss){
            if($ss->item){
                $activeSheet->setCellValue("A{$index}", $ss->item->nombre);
                $activeSheet->setCellValue("B{$index}", $ss->item->unidad);
                $activeSheet->setCellValue("C{$index}", $ss->item->descripcion);
                $activeSheet->setCellValue("D{$index}", $ss->item->precio_compra);
                $activeSheet->setCellValue("E{$index}", $ss->item->precio_venta);
            }
            $activeSheet->setCellValue("F{$index}", $ss->stock);
            $index++;  // incrmentamos en 1 fila
        }

        $filename = WWW_ROOT . "reporte_stock_almacen.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $filename_to_download = "reporte_stock.xlsx";

        $response = $this->response->withFile($filename, ['download' => true, 'name' => $filename_to_download]);
        return $response;
    }

    public function getStocksMenores()
    {
        $respuesta = [
            'success' => false,
            'data' => '',
            'message' => 'No se han encontrado datos',
        ];

        $stocks = $this->Stock->find();

        $stocks = $stocks
            ->select(
                [
                    'stock_total' => $stocks->func()->sum('Stock.stock'),
                    'item_nombre' => 'Items.nombre'
                ]
            )
            ->where(
                [
                    'Items.id IS NOT'   =>  null
                ]
            )
            ->contain(['Items'])
            ->order(['stock_total' => 'ASC'])
            ->group(['Stock.item_id'])
            ->limit(10);

        if($stocks)
        {
            $respuesta = [
                'success' =>  true,
                'data'    =>  $stocks,
                'message' =>  'Datos recuperados con éxito'
            ];
        }



        return $this->response->withType('application/json')
            ->withStringBody(json_encode($respuesta));

    }

    public function getSatusDeleteAlmacen ($almacen_id = '') {
        $flag = $this->Stock->find()->where(['almacen_id' => $almacen_id , 'stock >=' => 1])->first();
        return (bool) !$flag ;
    }
    public function moverTodo () {
        if($this->request->is('POST')){
            $data = $this->request->getData();
            $aid_salida = $data['almacen_salida_id'];
            $aid_entrada = $data['almacen_entrada_id'];
            
            if($this->actualizarStockMovimientoGeneral($aid_salida,$aid_entrada)){
                $this->Flash->success('Stock movido satisfactoriamente');
                
            }else{
                $this->Flash->error('Ha habido un error en el movimiento de la mercaderia');
            }
            $this->set('almacen_salida',$aid_salida);
            $this->set('almacen_entrada',$aid_entrada);
        }

        $top_links = [
            'title' => 'Movimiento de toda la mercaderia'
        ];

        /// Cargar selects
        $almacenes = $this->Almacenes->find('list');
        $this->set(compact('almacenes'));

        $this->set(compact('top_links'));
        $this->set('view_title', 'Mover Todo');
    }
    public function actualizarStockMovimientoGeneral($aid_salida, $aid_entrada){
        try{
            $almacen_salida = $this->Almacenes->find()->where(['id'=>$aid_salida])->first();
            $almacen_entrada = $this->Almacenes->find()->where(['id'=>$aid_entrada])->first();
            $ParteSalida = new ParteSalidasController();
            $parte_salida = $ParteSalida->saveSalida([
                'almacen_salida_id' => ($almacen_salida) ? $almacen_salida->id : 0,
                'almacen_destino_id' => ($almacen_entrada) ? $almacen_entrada->id : 0,
                'usuario_id' => $this->usuario_sesion['id'],
                'descripcion' => "Desde mover mercaderia",
                'fecha_emision' => date("Y-m-d")
            ]);

            $ParteEntrada = new ParteEntradasController();
            $parte_entrada = $ParteEntrada->saveEntrada([
                'parte_salida_id' => $parte_salida ? $parte_salida->id : 0,
                'almacen_id' => ($almacen_entrada) ? $almacen_entrada->id : 0,
                'usuario_id' => $this->usuario_sesion['id'],
                'descripcion' => "Desde mover mercaderia",
                'fecha' => date("Y-m-d"),
            ]);

            $items = $this->Items->find();
            foreach ( $items as $k => $item) {
                $stock_alm_salida = $this->Stock->find()->where(['almacen_id' => $aid_salida, 'item_id' => $item->id])->first();
                
                if($stock_alm_salida && $stock_alm_salida->stock >= 1){

                    $this->StockManager->retirarStock(
                        $item->id,
                        $aid_salida,
                        $stock_alm_salida->stock,
                        "MOVIMIENTO",
                        "SALIDA DE MERCADERIA"
                    );
                    $ParteEntradaRegistros = new ParteEntradaRegistrosController();
                    $parte_entrada_registro = $ParteEntradaRegistros->saveRegistro([
                        'parte_entrada_id' => $parte_entrada->id,
                        'item_id' => $item->id,
                        'item_index' => $k+1,
                        'cantidad' => $stock_alm_salida->stock,
                    ]);

                    // llegada
                    $this->StockManager->ingresarStock(
                        $item->id,
                        $aid_entrada,
                        $stock_alm_salida->stock,
                        "MOVIMIENTO",
                        "ENTRADA DE MERCADERIA"
                    );
                    $ParteSalidaRegistros = new ParteSalidaRegistrosController();
                    $parte_salida_registro = $ParteSalidaRegistros->saveRegistro([
                        'parte_salida_id' => $parte_salida->id,
                        'item_id' => $item->id,
                        'item_index' => $k+1,
                        'cantidad' => $stock_alm_salida->stock,
                    ]);
                }
            }
            return true;
        }catch(\Throwable $e){
            return false;
        }



    }
}
