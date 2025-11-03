<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client as httpClient;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Personas Controller
 *
 * @property \App\Model\Table\PersonasTable $Personas
 * @method \App\Model\Entity\Persona[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PersonasController extends AppController
{

    private $emisoresCtrl = null;

    public function initialize(): void
    {
        parent::initialize();

        $this->Cuentas = $this->fetchTable("Cuentas");
        $this->CuentaPersonas = $this->fetchTable("CuentaPersonas");
        $this->CuentaCanalLlegadas = $this->fetchTable("CuentaCanalLlegadas");
        $this->Usuarios = $this->fetchTable("Usuarios");
        $this->Cotizaciones = $this->fetchTable("Cotizaciones");
        $this->Ventas = $this->fetchTable("Ventas");
        $this->VentaRegistros = $this->fetchTable("VentaRegistros");
        $this->DirectorioPersonas = $this->fetchTable("DirectorioPersonas");

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function beforeRender(\Cake\Event\EventInterface  $event)
    {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }
    public function index()
    {

        $personas = $this->Personas->find('all');
        $opt_dni = $this->request->getQuery("opt_dni");
        $opt_nombre = $this->request->getQuery("opt_nombre");
        $opt_apellido = $this->request->getQuery("opt_apellido");
        $exportar = $this->request->getQuery("exportar");

        if (isset($opt_dni)) {
            $personas->andWhere(['dni LIKE' => "%{$opt_dni}%"]);
        }
        if (isset($opt_nombre)) {
            $personas->andWhere(['nombres LIKE' => "%{$opt_nombre}%"]);
        }
        if (isset($opt_apellido)) {
            $personas->andWhere(['apellidos LIKE' => "%{$opt_apellido}%"]);
        }

        if ($exportar == 1) {
            return $this->exportarContactos($personas);
            exit;
        }

        $this->paginate = [
            'limit' => 10,
        ];
        $personas = $this->paginate($personas);
        $this->set(compact('personas'));

        $top_links = [
            'title' => "Personas Naturales",
            'links' => [
                'btnNuevo' => [
                    'name' => "<i class='fas fa-plus fa-fw'></i> Nueva Persona",
                    'params' => []
                ],
                'btnCuentas' => [
                    'name' => "<i class='fas fa-database fa-fw'></i> Empresas",
                    'params' => [
                        'controller'    =>  'Cuentas'
                    ]
                ],
            ],
        ];

        $this->set("opt_dni", $opt_dni);
        $this->set("opt_nombre", $opt_nombre);
        $this->set("opt_apellido", $opt_apellido);
        $this->set("view_title", "Personas");
        $this->set("top_links", $top_links);
    }

    /**
     * View method
     *
     * @param string|null $id Persona id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $persona = $this->Personas->get($id, [
            'contain' => ['CuentaPersonas'],
        ]);

        $this->set('persona', $persona);

        $top_links = [
            'title' => 'Datos de la Persona'
        ];
        $this->set(compact('top_links'));

        $this->set('view_title', 'Datos de la Persona');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $persona = $this->Personas->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $persona = $this->Personas->patchEntity($persona, $data);
            $persona->cuenta_id = $this->Auth->user("id");
            if ($this->Personas->save($persona)) {
                $this->Flash->success(__('El paciene fue agregado con éxito'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('El paciene no pudo ser agregado, intente de nuevo'));
        }
        $cuentas = $this->Personas->Cuentas->find('list', ['limit' => 200]);
        $this->set(compact('persona', 'cuentas'));
        $top_links = [
            'title' => "Lista de Personas",
            'links' => [
                'btnBack' => [
                    'name' => "<i class='fas fa-plus fa-fw'></i> Nuevo",
                    'params' => [
                        'controller' => 'Personas',
                        'action' => 'add',
                    ]
                ],
            ],
        ];
        $this->set("view_title", "Diagnosticos");
        $this->set("top_links", $top_links);
    }

    public function getJsonAjax($id = ''){

        $result = [
            'status'    =>  'e',
            'data'      =>  'sin datos'
        ];

        $p = $this->Personas->find()->where(['id' => $id])->first();
        if ($p){
            $result = [
                'status'    =>  'ok',
                'data'      =>  $p->toArray()
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));

    }


    /**
     * Edit method
     *
     * @param string|null $id Persona id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $persona = $this->Personas->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $persona = $this->Personas->patchEntity($persona, $data);
            if ($this->Personas->save($persona)) {
                $this->Flash->success(__('El paciene fue modificado con éxito'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo modificar el Paciente, intente de nuevo'));
        }
        $cuentas = $this->Personas->Cuentas->find('list', ['limit' => 200]);
        $this->set(compact('persona', 'cuentas'));

        $top_links = [
            'title' => "Editar registro",
            'links' => [
                'btnBack' => [
                    'name' => "<i class='fas fa-chevron-left fa-fw'></i> Atras",
                    'params' => [
                        'controller' => 'Personas',
                        'action' => 'index',
                    ]
                ],
            ],
        ];
        $this->set("view_title", "Editar");
        $this->set("top_links", $top_links);
    }

    public function deleteAll(){

        $result = [
            'success'   =>  false,
            'message'   =>  'incorrect-method',
            'data'      =>  ''
        ];

        if ($this->request->is(['DELETE', 'POST'])){

            $ids = $this->request->getData('ids');

            $personas = $this->Personas->find()->where(['id IN' => $ids]);

            $cuentaPersonas = $this->CuentaPersonas->find()->where(['CuentaPersonas.persona_id IN ' => $ids]);

            foreach ($cuentaPersonas as $cp){
                $this->CuentaPersonas->delete($cp);
            }

            foreach ($personas as $p){
                $this->Personas->delete($p);
            }

            $this->Flash->success('Registros eliminados correctamente');

            $result = [
                'success'   =>  true,
                'message'   =>  'Eliminacion exitosa',
                'data'      =>  ""
            ];



        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }



    public function save() {
        $respuesta = [
            'success' => false,
            'data' => '',
            'message' => 'No se recibieron datos'
        ];

        if ($this->request->is('POST')) {
            try {
                $data = $this->request->getData();
                $id = isset($data['id']) ? $data['id'] : 0;

                $persona = $this->Personas->find()->where(['id' => $id])->first();
                if (!$persona){
                    $persona = $this->Personas->newEntity($data);
                }

                $persona = $this->Personas->patchEntity($persona, $data);
                if(isset($data['fecha_nacimiento'])){
                    $persona->fecha_nacimiento = date('Y-m-d H:i:s',strtotime($this->request->getData('fecha_nacimiento',null)));
                }

                if (intval($id) > 0) {
                    $persona->id = $id;
                }

                $persona = $this->Personas->save($persona);

                $respuesta = [
                    'success' => true,
                    'data' => $persona,
                    'message' => 'Operacion exitosa'
                ];

                if ( isset($data['cuenta_id']) && intval($data['cuenta_id']) > 0) {
                    $cc = $this->CuentaPersonas->newEmptyEntity();
                    $cc->persona_id = $persona->id;
                    $cc->cuenta_id = intval($data['cuenta_id']);
                    $cc->cargo = $data['cargo_empresa'];

                    $cc = $this->CuentaPersonas->save($cc);

                    $respuesta = [
                        'success' => true,
                        'data' => [$persona, $cc],
                        'message' => 'Operacion exitosa 1'
                    ];
                }

                if (isset($data['empresa_id']) && $data['empresa_id'] != "") {
                    $cc = $this->DirectorioPersonas->newEmptyEntity();
                    $cc->persona_id = $persona->id;
                    $cc->empresa_id = intval($data['empresa_id']);
                    $cc->rol = $data['cargo_empresa'];

                    $cc = $this->DirectorioPersonas->save($cc);

                    $respuesta = [
                        'success' => true,
                        'data' => [$persona, $cc],
                        'message' => 'Operacion exitosa 2'
                    ];
                }

            } catch (\Throwable $e) {
                $respuesta = [
                    'success' => false,
                    'data' => '',
                    'message' => 'ERROR: ' . $e->getMessage()
                ];
            }
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function getOne($persona_id){
        try{
            $persona = $this->Personas->find()->where(['id'=>$persona_id])->first();
            if($persona){
                $respuesta = [
                    'success' => true,
                    'data' => $persona,
                    'message' => 'Busqueda exitosa',
                ];
            }else{
                $respuesta = [
                    'success' => false,
                    'data' => $persona,
                    'message' => 'El cliente relacionado con esta venta no existe o se ha eliminado',
                ];

            }

        }  catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => 'ERROR: '.$e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }

    public function apiGetReporteNumerologico($persona_id,$empresa_id=''){

        $respuesta =
        [
            'success'   =>  false,
            'data'      =>  '',
            'message'   =>  ''
        ];

        $persona = $this->Personas
        ->find()
        ->where(
            [
                'id'    =>  $persona_id
            ]
        )->first();


        if($persona){
            if(isset($persona->fecha_nacimiento)  && $persona->fecha_nacimiento != null && $persona->fecha_nacimiento != ''){

                $empresa = null;
                if($empresa_id != ''){
                    $empresaObj = $this->Cuentas->find()->where(['id'   =>  $empresa_id])->first();
                    $empresa = $empresaObj ? $empresaObj : null;
                }else{
                    $empresas = $this->Cuentas->find()
                    ->contain(['Personas']);
                    $verf =false;

                    foreach($empresas as $emp){
                        foreach($emp->personas as $emp_persona){
                            if($emp_persona->id == $persona->id){
                                $empresa = $emp;
                                $verf = true;
                            }
                        }
                        if($verf){
                            break;
                        }
                    }
                }


                $data = $this->getReporteNumerologico($persona,$empresa);
                if($data['success']){
                    $respuesta =
                    [
                        'success'   =>  true,
                        'data'      =>  $data['data'],
                        'message'   =>  'Consulta numerológica satisfactoria'
                    ];
                }
            }else{
                $respuesta =
                [
                    'success'   =>  false,
                    'data'      =>  '',
                    'message'   =>  'La persona indicada no cuenta con fecha de nacimiento'
                ];
            }
        }else{
            $respuesta  =
            [
                'success'   =>  false,
                'data'      =>  '',
                'message'   =>  'No se ha encontrado a la persona solicitada'
            ];
        }

        return $this->response
        ->withType('application/json')
        ->withStringBody(json_encode($respuesta));
    }


    public function exportarContactos($personas, $params = [])
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Personas");
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Personas');
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);
        $index = 2;
        // imprimimos los parámetros del reporte
        foreach ($params as $k => $v) {
            $activeSheet->setCellValue("A{$index}", $k);
            $activeSheet->setCellValue("B{$index}", $v);
            $index++;  // incrmentamos 1 fila
        }
        $index++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:J{$index}")->getFont()->setBold(true);

        $activeSheet->getColumnDimension('A')->setWidth(15);
        $activeSheet->getColumnDimension('B')->setWidth(30);
        $activeSheet->getColumnDimension('C')->setWidth(20);    //cargo
        $activeSheet->getColumnDimension('D')->setWidth(30);
        $activeSheet->getColumnDimension('E')->setWidth(20);
        $activeSheet->getColumnDimension('F')->setWidth(35); // correo
        $activeSheet->getColumnDimension('G')->setWidth(20);
        $activeSheet->getColumnDimension('H')->setWidth(20);
        $activeSheet->getColumnDimension('I')->setWidth(20);
        $activeSheet->getColumnDimension('J')->setWidth(20);
        $activeSheet->setCellValue("A{$index}", "DNI");
        $activeSheet->setCellValue("B{$index}", "Nombres");
        $activeSheet->setCellValue("C{$index}", "Cargo");
        $activeSheet->setCellValue("D{$index}", "Domicilio");
        $activeSheet->setCellValue("E{$index}", "F. Nacimiento");
        $activeSheet->setCellValue("F{$index}", "Correo");
        $activeSheet->setCellValue("G{$index}", "Telefono");
        $activeSheet->setCellValue("H{$index}", "Cel. Trabajo");
        $activeSheet->setCellValue("I{$index}", "Cel. Personal");
        $activeSheet->setCellValue("J{$index}", "Whatsapp");


        $index++; // agreamos 1 fila más
        foreach ($personas as $cc) {
            $activeSheet->setCellValueExplicit(
                "A{$index}",
                $cc->dni,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            // $activeSheet->setCellValue("A{$index}", $cc->dni);
            $activeSheet->setCellValue("B{$index}", "{$cc->nombres} {$cc->apellidos}");
            $activeSheet->setCellValue("C{$index}", $cc->cargo_empresa);

            $activeSheet->setCellValue("D{$index}", $cc->domicilio);
            $activeSheet->setCellValue("E{$index}", $cc->fecha_nacimiento != '' && $cc->fecha_nacimiento != null ? $cc->fecha_nacimiento->format("d/m/Y") : '');
            $activeSheet->setCellValue("F{$index}", $cc->correo);

            $activeSheet->setCellValueExplicit(
                "G{$index}",
                $cc->telefono,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            // $activeSheet->setCellValue("G{$index}", );

            $activeSheet->setCellValueExplicit(
                "H{$index}",
                $cc->celular_trabajo,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            // $activeSheet->setCellValue("H{$index}", $cc->celular_trabajo);

            $activeSheet->setCellValueExplicit(
                "I{$index}",
                $cc->celular_personal,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            // $activeSheet->setCellValue("I{$index}", $cc->celular_personal);

            $activeSheet->setCellValueExplicit(
                "J{$index}",
                $cc->whatsapp,
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            // $activeSheet->setCellValue("J{$index}", $cc->whatsapp);


            $index++;
        }
        $index++;   // agrega una fila
        // resumen del reporte, montos totales
        /*$activeSheet->setCellValue("H{$index}", $total_pagos);
        $activeSheet->setCellValue("I{$index}", $total_deuda);
        $activeSheet->setCellValue("J{$index}", $total);*/
        $filename = "reporte_contactos_{$this->usuario_sesion['id']}.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        return $this->response->withFile($filename, ['download' => true, 'name' => "reporte_de_contactos_al_" . date("Y_m_d") . ".xlsx"]);
    }

}
