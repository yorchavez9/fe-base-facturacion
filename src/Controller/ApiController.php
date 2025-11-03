<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Security;
use Cake\Http\ServerRequest;

class ApiController extends AppController
{


    protected $DirectorioEmpresas = null;
    protected $UbiDistritos = null;
    protected $ItemMarcas = null;
    protected $Items = null;
    protected $ItemCategorias = null;
    protected $Cuentas = null;
    protected $Personas = null;

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        if ($this->request->is('options')) {
            $this->setCorsHeaders();
            return $this->response;
        }
    }

    private function setCorsHeaders()
    {
        $this->response = $this->response->cors($this->request)
            ->allowOrigin(['*'])
            ->allowMethods(['*'])
            ->allowHeaders(['*'])
            ->allowCredentials(['true'])
            ->exposeHeaders(['Link'])
            ->maxAge(300)
            ->build();
    }


    public function getUbigeo()
    {
        $q = $this->request->getQuery("query");


        $items = $this->UbiDistritos->find()->where(['info_busqueda LIKE ' => "%{$q}%"]);
        foreach ($items as $d) {
            $d->value = $d->info_busqueda;
        }

        header("Content-type:text/json; Charset=UTF-8");
        echo json_encode([
            "query" => $q,
            "suggestions" => $items
        ]);
        exit;
    }

    public
    function initialize(): void
    {
        parent::initialize();
        header("Access-Control-Allow-Origin: *");

        $this->DirectorioEmpresas = $this->fetchTable("DirectorioEmpresas");
        $this->UbiDistritos = $this->fetchTable("UbiDistritos");
        $this->ItemMarcas = $this->fetchTable("ItemMarcas");
        $this->Items = $this->fetchTable("Items");
        $this->ItemCategorias = $this->fetchTable("ItemCategorias");
        $this->Cuentas = $this->fetchTable("Cuentas");
        $this->Personas = $this->fetchTable("Personas");



        $this->Authentication->allowUnauthenticated([
            'getInfo',
        ]);
    }

    public function ajaxConsultaProveedores()
    {

        $q = $this->request->getQuery("query", '');
        // filtramos por organización
        $items = $this->DirectorioEmpresas->find("all");

        // filtramos por info busqueda
        $items = $items->andWhere(['info_busqueda LIKE' => "%{$q}%"]);
        foreach ($items as $c) {
            $c['value'] = $c['info_busqueda'];
        }
        echo json_encode([
            "query" => $q,
            "suggestions" => $items
        ]);
        exit;
    }

    public function ajaxConsultaCuentas(){
        $q = $this->request->getQuery("query");

        $cuentas = $this->Cuentas->find("all")->where(['info_busqueda LIKE' => "%{$q}%" ]);
        foreach ($cuentas as $c){$c['value'] = $c['info_busqueda'];}

        echo json_encode([
            "query" =>  $q,
            "suggestions"   =>  $cuentas
        ]);
        exit;
    }

    public function ajaxConsultaPersonas(){
        $q = $this->request->getQuery("query");
        $items = $this->Personas->find()->where(['info_busqueda LIKE' => "%{$q}%" ]);
        foreach ($items as $c){
            $c['value'] = $c['info_busqueda'];
            $c['nombre_completo'] = $c['nombres'] . " " . $c['apellidos'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                "query" =>  $q,
                "suggestions"   =>  $items
            ]));

    }



    /**
     * Function para obtener el detalle de un item
     * desde el codigo ingresado
     */
    public function getItemByCode()
    {
        $codigo = $this->request->getQuery("codigo");

        $item = $this->Items->find()->where(['codigo' => $codigo])->first();

        if ($item){
            echo json_encode([
                'estado'    =>  'ok',
                'data'      =>  $item
            ]);
        }else{
            echo json_encode([
                'estado'    =>  'e',
                'data'      =>  'No existe el producto'
            ]);
        }
        exit;
    }

    /**
     * Function utilizada a través de ajax para consultar stock, codigo o nombre de productos,
     * antes de ser ingresado en la cotización y/o venta
     */
    public function getItems(){

        $q = $this->request->getQuery("q");


        $items = $this->Items->find()->limit(21)->where(
            [
                'OR'    =>
                [
                    'info_busqueda LIKE' => "%{$q}%",
                    'descripcion_alternativa LIKE'  =>  "%{$q}%"
                ]
            ]);
        $data = [];
        foreach ($items as $ii){

            $categoria = $this->ItemCategorias->find()->where(['id' => ($ii->categoria_id ?? "0")])->first();
            $marca = $this->ItemMarcas->find()->where(['id' => ($ii->marca_id ?? "0")])->first();

            $row = [];
            $row[] = ($categoria) ? $categoria->nombre : "";
            $row[] = ($marca) ? $marca->nombre : "";
            $row[] = $ii->codigo;
            $row[] = $ii->nombre;
            $row[] = $ii->ubicacion;
            
            $row[] = $ii->precio_venta;
            $row[] = $ii->tipo_moneda;
            $data[] = $row;
        }

        $response = [ "data"  =>  $data];

        echo json_encode($response);
        exit;
    }


    /**
     * Utilizado en el formulario de compras
     * para cargar los items
     */
    public function getItemsAc(){
        $q = $this->request->getQuery("query");


        $items = $this->Items->find()->limit(20)->where([
            'info_busqueda LIKE' => "%{$q}%"
        ]);



        foreach ($items as $ii){
            $marca = $this->ItemMarcas->find()->where(['id' => $ii->marca_id])->first();
            $ii->marca = $marca;
            $ii->value = $ii->info_busqueda;   }

        echo json_encode([
            "query" =>  $q,
            "suggestions"   =>  $items
        ]);
        exit;
    }

    /**
     * Utilizado en el formulario de compras
     * para autocompletar las marcas
     */
    public function getMarcasAc(){
        $q = $this->request->getQuery("query");
        $marcas = $this->ItemMarcas->find()->limit(20)->where([ 'nombre LIKE ' => "%{$q}%"]);
        foreach ($marcas as $ii){
            $ii->value = $ii->nombre;
        }

        echo json_encode([
            "query" =>  $q,
            "suggestions"   =>  $marcas
        ]);
        exit;
    }



    public function getItemsNew(){

        $q = $this->request->getQuery("q",'');
        $opt_unidad = $this->request->getQuery("opt_unidad", '');
        $opt_categoria = $this->request->getQuery("opt_categoria", '');
        $page = intval($this->request->getQuery("page",1));
        $limit = intval($this->request->getQuery('limit',10));



        $items = $this->Items->find()
        ->where(
            [
                'OR'    =>
                [
                    'info_busqueda LIKE' => "%{$q}%",
                    'descripcion_alternativa LIKE'  =>  "%{$q}%"
                ]
            ]
        );
        if($opt_unidad != ''){
            $items = $items->andWhere(['unidad' => $opt_unidad]);
        }
        if($opt_categoria != ''){
            $items = $items->andWhere(['categoria_id' => $opt_categoria]);
        }

        $pagesNumber = ($items->count() <= 0) ? 0 : ceil( $items->count() / $limit  ) ;
        if($pagesNumber < $page){
            $page = 1;
        }


        $data = [];
        if($items && !$items->all()->isEmpty()){
            $items = $this->paginate($items, ['maxLimit' => 10]);
        }

        $data['paginacion'] =   [];
        $data['items']      =   [];
        $data['paginacion'] =
            [
                'pagesNumber'       =>    $pagesNumber,
                'totalRowsNumber'        =>    $items->count(),
                'page'              =>    $page,
            ];


        foreach ($items as $ii){

            $categoria = $this->ItemCategorias->find()->where(['id' => ($ii->categoria_id ?? "0")])->first();

            $row = [];
            $row['id']      =   $ii->id;
            $row['unidad']  =   $ii->unidad;
            $row['img_ruta']    = $ii->img_ruta ;
            $row['categoria'] = ($categoria) ? $categoria->nombre : "";

            $row['codigo'] = $ii->codigo;
            $row['nombre'] = $ii->nombre;
            $row['ubicacion'] = $ii->ubicacion;
            $row['precio_venta'] = $ii->precio_venta;
            $row['tipo_moneda'] = $ii->tipo_moneda;
            $row['inc_igv'] = $ii->inc_igv;
            $data['items'][] = $row;
        }

        $respuesta =
        [
            'success'   =>  true,
            'data'      =>  $data,
            'message'   =>  'Consulta exitosa'
        ];

        return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode($respuesta));

    }

    public function getItemCategorias() {
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
}
