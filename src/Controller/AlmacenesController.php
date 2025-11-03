<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\AppController;
use Dompdf\Dompdf;

/**
 * Almacenes Controller
 *
 * @property \App\Model\Table\AlmacenesTable $Almacenes
 * @method \App\Model\Entity\Almacen[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AlmacenesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function initialize():void {
        parent::initialize();
    }
    
    public function beforeRender(\Cake\Event\EventInterface $event) {
        parent::beforeRender($event);
    }

    public function index()
    {
        $opt_nombre = $this->request->getQuery('opt_nombre');
        
        $almacenes = $this->Almacenes->find()->where(['Almacenes.nombre LIKE ' => "%{$opt_nombre}%" ]);
        $almacenes = $this->paginate($this->Almacenes->find(), ['maxLimit' => 10]);
        $top_acciones = [];
        $top_acciones['links'] = [];
        $top_acciones['title'] = "Establecimientos";

        $top_acciones['functions'] = [
            'btnA' => [
                'function'  =>  'Establecimientos.add()',
                'name'  =>    "<i class='fas fa-plus fa-fw'></i> Establecimientos" ,
            ]
        ];

        $this->set("view_title", "Establecimientos");
        $this->set("top_links", $top_acciones);
        $this->set("opt_nombre", $opt_nombre);

        $this->set(compact('almacenes'));
        $this->set('_serialize', ['almacenes']);
        $this->set("ubicaciones", $this->getUbicaciones());
    }

    public function view($id = null)
    {
        $almacen = $this->Almacenes->get($id);

        $this->set('almacen', $almacen);
        $this->set('_serialize', ['almacen']);
    }

  
    public function add()
    {
        $almacen = $this->Almacenes->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $alm_cod = $this->Almacenes->find()->where(['codigo' => $data['codigo']])->first();

            if (!$alm_cod) {
                $almacen = $this->Almacenes->patchEntity($almacen, $data );
                $datos_ubi = $this->getDireccionFromUbigeo($almacen->ubigeo);
                $almacen->departamento = $datos_ubi['departamento'];
                $almacen->provincia = $datos_ubi['provincia'];
                $almacen->distrito = $datos_ubi['distrito'];
                $almacen->info_busqueda = $this->generarInfoBusqueda($almacen);
                if ($this->Almacenes->save($almacen)) {
                    $this->Flash->success("El Almacen ha sido registrado con éxito");
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error("Ha ocurrido un error, intente nuevamente");
                }
            } else {
                $this->Flash->error("El Establecimiento con código {$data['codigo']} ya ha sido registrado con anterioridad");
            }
        }
        $top_acciones = [
            'title'     =>  'Registro de Nuevo Establecimiento',
            'links'     =>  [
                'btnBack'  =>  [
                    'name'  =>    "<i class='fas fa-database fa-fw'></i> Establecimientos" ,
                    'params'    =>  [
                        'controller' =>  'Almacenes',
                        'action'     => 'index',
                    ]
                ],

            ]

        ];
        $this->set("view_title", "Establecimientos");
        $this->set("ubicaciones", $this->getUbicaciones());
        $this->set("top_links", $top_acciones);
        $this->set(compact('almacen'));
        $this->set('_serialize', ['almacen']);
    }

    public function edit($id = null)
    {
        $almacen = $this->Almacenes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $almacen = $this->Almacenes->patchEntity($almacen, $data );
            $datos_ubi = $this->getDireccionFromUbigeo($almacen->ubigeo);
            $almacen->departamento = $datos_ubi['departamento'];
            $almacen->provincia = $datos_ubi['provincia'];
            $almacen->distrito = $datos_ubi['distrito'];
            $almacen->info_busqueda = $this->generarInfoBusqueda($almacen);
            if ($this->Almacenes->save($almacen)) {
                $this->Flash->success("El Establecimiento ha sido actualizado con éxito");
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error("Ha ocurrido un error, por favor intente nuevamente");
            }
        }

        $top_acciones = [
            'title'     =>  "Modificando {$almacen->nombre}",
            'links'     =>  [

            ]

       ];

        $this->set("view_title", "Establecimientos");
        $this->set("top_links", $top_acciones);
        $this->set("ubicaciones", $this->getUbicaciones());

        $this->set(compact('almacen'));
        $this->set('_serialize', ['almacen']);
        $this->set("title", "Modificar Establecimiento");
        $this->set("establecimientos", $this->getEstablecimientos());
    }

    private function checkCantAlmacenesEstablecimientosAtEdit($es_tienda_old_value, $es_tienda_new_value){
        if($es_tienda_old_value == $es_tienda_new_value){
            return [
                'message'   =>  '',
                'success'   =>  true
            ];
        }else{
            if($es_tienda_new_value == 1 ){
                $flag_disponiblidad = $this->verificarCantidadMaximaEstablecimientos();
                $mensaje = !$flag_disponiblidad ? 'No tiene permitido ingresar mas establecimientos' : '';
            }
            return ['message' => $mensaje , 'success' => $flag_disponiblidad];
        }

    }


    private function verificarCantidadMaximaEstablecimientos(){
        $cant_max_establecimientos = intval($this->getConfig('cant_max_establecimientos','-1'));

        if($cant_max_establecimientos == -1){
            return true;
        }

        $cant_establecimientos = $this->Almacenes
            ->find()
            ->where(
                [
                    'es_tienda' => '1'
                ])
            ->count();

        if($cant_establecimientos < $cant_max_establecimientos){
            return true;
        }
        return false;

    }



    /**
     * Devuelve un array con el nombre del departamento, provincia y distrito
     * @param $ubigeo
     * @return array
     */
    private function getDireccionFromUbigeo($ubigeo = ''){
        $distrito = $this->fetchTable('UbiDistritos')->find()->where(['id' => $ubigeo ])->first();
        if(!$distrito){
            return [
                'distrito'  =>  '',
                'provincia'  =>  '',
                'departamento'  =>  '',
            ];
        }
        $provincia = $this->fetchTable('UbiProvincias')->get($distrito->provincia_id);
        $departamento = $this->fetchTable('UbiRegiones')->get($distrito->region_id);

        return [
            'distrito'  =>  $distrito->nombre,
            'provincia'  =>  $provincia->nombre,
            'departamento'  =>  $departamento->nombre,
        ];

    }

    /**
     * Delete method
     *
     * @param string|null $id Almacen id.
     * @return \Cake\Http\Response Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $almacen = $this->Almacenes->find()->where(['id' => $id ])->first();
        if(!$almacen){
            $this->Flash->error("El Almacén no existe.");
            return $this->redirect(['action' => 'index']);
        }
        // $this->loadModel("Stock");
        // $stockCtrl = new StockController();
        // $almacen_id = $almacen->id;
        // if ( $stockCtrl->getSatusDeleteAlmacen($almacen_id) ) {
            if( $this->Almacenes->delete($almacen) ){
                // $this->Stock->deleteAll(['almacen_id' => $almacen_id]);
                $this->Flash->success("El almacen se ha eliminado con éxito");
            }else{
                $this->Flash->error("Ocurrió un error al eliminar el Almacén.");
            }
        // } else {
        //     $this->Flash->error("El almacen cuenta con Stock, no se puede eliminar.");
        // }

        return $this->redirect(['action' => 'index']);
    }

    private function generarInfoBusqueda($almacen){
        $info_busqueda = "{$almacen->nombre} - {$almacen->direccion}";
        return $info_busqueda;
    }

    /**
     * Devuelve un objeto json con todos los registros de almacenes, sin filtrar
     * @return \Cake\Http\Response
     */
    public function getAll() {
        $items = $this->Almacenes->find();

        if ($items) {
            $respuesta = [
                'success' => true,
                'data' => $items,
                'message' => 'Busqueda exitosa'
            ];
        } else {
            $respuesta = [
                'success' => false,
                'data' => $items,
                'message' => 'Error en la bsuqueda'
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function getOne($id = "0") {
        $item = $this->Almacenes->get($id);

        if ($item) {
            $respuesta = [
                'success' => true,
                'data' => $item,
                'message' => 'Busqueda exitosa'
            ];
        } else {
            $respuesta = [
                'success' => false,
                'data' => $item,
                'message' => 'Error en la bsuqueda'
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }
    public function getAlmacenStock($id = "0") {
        $item = $this->Almacenes->find()->where(['id' => $id])->first();
        if ($item) {
            $cantidad = $this->Stock->find()->where(['almacen_id' => $id])->count();
            $respuesta = [
                'success' => true,
                'data' => $item,
                'message' => 'Busqueda exitosa',
                'cantidad' => ceil($cantidad/250),
            ];
        } else {
            $respuesta = [
                'success' => false,
                'data' => $item,
                'message' => 'Error en la bsuqueda',
                'cantidad' => 0,
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }


    public function exportarStockPdf($id){
        ini_set('memory_limit', '-1');

        $this->paginate = [
            'limit' => $this->request->getQuery('limit',250)
        ];


        $almacen = $this->Almacenes->find()->where(['id' => (int)$id ])->first();
        $stocks = $this->Stock->find()->where(['almacen_id' => (int)$id ])->contain(['Items'])->limit($limite);
        $stocks = $this->paginate($stocks);
        if($almacen){

            $this->viewBuilder()->setLayout("none");
            $this->viewBuilder()->setVars([
                'stocks'     => $stocks,
                'almacen'     => $almacen,
            ]);

            $html = $this->render("exportarStockPdf");

            $dompdf = new Dompdf();

            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled',true);
            $dompdf->setOptions($options);

            $dompdf->loadHtml($html);
            $dompdf->setPaper("A4");
            $dompdf->render();
            // $nombre = "Reporte_stock" ;
            $dompdf->stream("Reporte_stock.pdf", ['Attachment' => true]);
        }
    }
    public function exportarStockExcel($id){
        $almacen = $this->Almacenes->find()->where(['id' => (int)$id ])->first();
        $stocks = $this->Stock->find()->where(['almacen_id' => (int)$id ])->contain(['Items']);

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


        $activeSheet->setCellValue("A{$index}", $almacen->nombre );
        $activeSheet->setCellValue("B{$index}", $almacen->direccion );

        $index++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:L{$index}")->getFont()->setBold(true);
        $activeSheet->getColumnDimension('A')->setWidth(20);
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
        $emisorCtrl = new SunatFeEmisoresController();
        $filename = $emisorCtrl->getBasePathMedia() . "reporte_stock_almacen.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $filename_to_download = "reporte_stock.xlsx";

        $response = $this->response->withFile($filename, ['download' => true, 'name' => $filename_to_download]);
        return $response;
    }

    public function establecimientos() {
        $opt_nombre = $this->request->getQuery('opt_nombre');
        $this->paginate = [
            'limit' => 10,
            'conditions'    =>  ['nombre LIKE ' => "%{$opt_nombre}%" , 'es_tienda' => '1']
        ];
        $almacenes = $this->paginate($this->Almacenes->find());

        $this->set('cant_max_establecimientos', $this->getConfig('cant_max_establecimientos'));
        $this->set('establecimientos_count', $this->Almacenes->find()->where(['es_tienda' => '1'])->count());

        $top_acciones = [];
        $top_acciones['links'] = [];
        $top_acciones['title'] = "Establecimientos";

        // permiso de agregar almacen
        if ($this->isAllowed('ALMACENES_ADD')):
            $top_acciones['links'] = [
                'btnNuevo'  =>  [
                    'name'    =>     "<i class='fas fa-plus fa-fw'></i> Nuevo Establecimiento",
                    'params'    =>     [
                        'controller' =>  'Almacenes',
                        'action'     =>  'add',
                        '?' => [ 'est_check' => '1' ]
                    ]
                ]
            ];
        endif;

        $this->set("view_title", "Almacenes");
        $this->set("top_links", $top_acciones);
        $this->set("opt_nombre", $opt_nombre);

        $this->set(compact('almacenes'));
        $this->set('_serialize', ['almacenes']);
    }
    public function getEstablecimientos (){
        return $this->Almacenes->find('list' , [ 'keyField' => 'id' , 'valueField' => 'nombre'])->where(['es_tienda' => '1' ]);
    }
    public function convertirEnEstablecimiento() {
        $data = $this->request->getData();
        $id = $data['almacen_id'] ?? 0;
        if(!$this->verificarCantidadMaximaEstablecimientos()){
            $respuesta = [
                'success' => false,
                'data' => null,
                'message' => 'No tiene permitido registrar mas establecimientos',
            ];
            return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
        }
        $item = $this->Almacenes->find()->where(['id' => $id , 'es_tienda' => '0'])->first();
        if ($item) {
            $exist = $this->Almacenes->find()->where(['id !=' => $id , 'codigo' => $data['codigo'] ])->first();
            if($exist){

                $respuesta = [
                    'success' => false,
                    'data' => $item,
                    'message' => 'El código ya esta en uso',
                ];
            }else{
                $item->codigo = $data['codigo'];
                $item->es_tienda = '1';
                $item->establecimiento_id = 0;
                $this->Almacenes->save($item);
                $respuesta = [
                    'success' => true,
                    'data' => $item,
                    'message' => 'Almacen convertido.',
                ];
            }
        } else {
            $respuesta = [
                'success' => false,
                'data' => $item,
                'message' => 'El almacen no existe.',
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }
}
