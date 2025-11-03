<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\CanalLlegada;
use App\Model\Entity\CuentaCanalLlegada;
use App\Model\Entity\CuentaTipo;
use Cake\Controller\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\Http\ServerRequest;

/**
 * Cuentas Controller
 *
 * @property \App\Model\Table\CuentasTable $Cuentas
 * @method \App\Model\Entity\Cuenta[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CuentasController extends AppController
{


    protected $Personas = null;
    protected $CuentaPersonas = null;
    protected $CuentaCanalLlegadas = null;
    protected $Usuarios = null;
    protected $Cotizaciones = null;
    protected $CuentaTipos = null;
    protected $Cuentas = null;
    protected $Ventas = null;
    protected $VentaRegistros = null;


    public function initialize(): void
    {
        parent::initialize();
        $this->Personas = $this->fetchTable("Personas");
        $this->CuentaPersonas = $this->fetchTable("CuentaPersonas");
        $this->CuentaCanalLlegadas = $this->fetchTable("CuentaCanalLlegadas");
        $this->Usuarios = $this->fetchTable("Usuarios");
        $this->Cotizaciones = $this->fetchTable("Cotizaciones");
        $this->CuentaTipos = $this->fetchTable("CuentaTipos");
        $this->Cuentas = $this->fetchTable("Cuentas");
        $this->Ventas = $this->fetchTable("Ventas");
        $this->VentaRegistros = $this->fetchTable("VentaRegistros");

    }

    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }

    public function contactos($cuenta_id = "")
    {
        $opt_dni = $this->request->getQuery("opt_dni", "");
        $opt_nombre = $this->request->getQuery("opt_nombre", "");
        $opt_apellido = $this->request->getQuery("opt_apellido", "");

        $cuentaPersonas = $this->CuentaPersonas->find()->where([
            'Personas.dni LIKE ' => "%{$opt_dni}%",
            'Personas.nombres LIKE ' => "%{$opt_nombre}%",
            'Personas.apellidos LIKE ' => "%{$opt_apellido}%",
        ])->contain(['Personas']);

        if ($cuenta_id != "") {
            $cuentaPersonas = $cuentaPersonas->andWhere(['CuentaPersonas.cuenta_id' => $cuenta_id]);
        }

        $ids = [0];

        foreach ($cuentaPersonas as $cp) {
            $ids[] = $cp->persona->id;
        }

        $personas = $this->Personas->find()->where([
            'id IN ' => $ids,
        ]);

        $this->paginate = [
            'limit' => 10,
        ];

        $personas = $this->paginate($personas);
        $this->set(compact('personas'));
        $cliente = $this->Cuentas->get($cuenta_id);

        $top_links = [];
        $top_links['title'] = "Listado de Contactos de {$cliente->razon_social}";

        $top_links['links'] = [];

        $top_links['links'] = [
            'btnNuevo' => [
                'name' => "<i class='fas fa-plus fa-fw'></i> Contacto",
                'params' => [],
            ]
        ];

        $this->set(compact('cliente'));
        $this->set("opt_dni", $opt_dni);
        $this->set("opt_nombre", $opt_nombre);
        $this->set("opt_apellido", $opt_apellido);
        $this->set("view_title", "Personas");
        $this->set("top_links", $top_links);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response
     */
    public function index()
    {
        $exportar = $this->request->getQuery("exportar");
        $nom = $this->request->getQuery('nom');
        $opt_ruc = $this->request->getQuery('opt_ruc', '');

        $this->set(compact('opt_ruc'));

        $this->paginate = [
            'limit' => 10,
        ];
        $cuentas = $this->Cuentas->find()->contain(["Personas"])->where([
            'Cuentas.estado' => 'ACTIVO',
        ])->orderDesc('Cuentas.id');

        if ($nom != "") {
            $cuentas = $cuentas->andWhere(['Cuentas.razon_social LIKE ' => "%{$nom}%"]);
        }

        if ($opt_ruc != "") {
            $cuentas = $cuentas->andWhere(['Cuentas.ruc LIKE ' => "%{$opt_ruc}%"]);
        }

        if ($exportar == 1) {
            return $this->exportarExcel($cuentas, [
                'Cotizaciones : ' => $nom,
                //                'Tipo de cliente: '  =>  $cliente_tipo_id
            ]);
            exit;
        } else if ($exportar == 2) {
            return $this->exportarContactos($cuentas);
            exit;
        }

        $cuentas = $this->paginate($cuentas);

        $top_links = [];
        $top_links['title'] = "Personas Jurídicas (Empresas)";
        $top_links['links'] = [];
        $top_links['links']['btnAdd'] = [
            'name' => "<i class='fas fa-plus fa-fw'></i> Nueva Empresa",
            'params' => [
                'controller' => 'Cuentas',
                'action' => 'add',
            ]
        ];

        $top_links['links']['btnPersonas'] = [
            'name' => "<i class='fas fa-database fa-fw'></i> Personas",
            'params' => [
                'controller' => 'Personas',
                'action' => 'Index',
            ]
        ];

        $this->set("view_title", "Clientes");
        $this->set("top_links", $top_links);
        $this->set("cuentas", $cuentas);
        $this->set("nom", $nom);
        $this->set('_serialize', ['clientes']);
    }

    public function exportarExcel($clientes, $params = [])
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Clientes");
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Clientes');
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
        $activeSheet->getColumnDimension('A')->setWidth(20);
        $activeSheet->getColumnDimension('B')->setWidth(20);
        $activeSheet->getColumnDimension('C')->setWidth(55);
        $activeSheet->getColumnDimension('D')->setWidth(30);
        $activeSheet->getColumnDimension('E')->setWidth(15);
        $activeSheet->setCellValue("A{$index}", "Nombre Comercial");
        $activeSheet->setCellValue("B{$index}", "RUC");
        $activeSheet->setCellValue("C{$index}", "Razón Social");
        $activeSheet->setCellValue("D{$index}", "Proforma");
        $activeSheet->setCellValue("E{$index}", "Proyecto");

        /* $total = 0;
         $total_pagos = 0;
         $total_deuda = 0;*/
        $index++; // agreamos 1 fila más
        foreach ($clientes as $v) {
            // $c=$this->Cotizaciones->get($v->id);
            //  $p=$this->Proyectos->get($v->id);
            //DEBE MOSTRAR SI EL CLIENTE TIENE UNA COTIZACION O UN PROYECTO
            $activeSheet->setCellValue("A{$index}", $v->nombre_comercial);
            $activeSheet->setCellValue("B{$index}", $v->ruc);
            $activeSheet->setCellValue("C{$index}", $v->razon_social);
            /*
             if($c){
                 //SI NO HAY COTIZACION O PROYECTO EL CAMPO DEBE ESTAR VACIO
               $activeSheet->setCellValue("D{$index}", $c->ascensor_nombre);
               }
             if($p){
               $activeSheet->setCellValue("E{$index}", $p->nombre);
               }
               */

            /* $total += $v->total;
             $total_pagos += $v->total_pagos;
             $total_deuda += $v->total_deuda;*/
            $index++;
        }
        $index++;   // agrega una fila
        // resumen del reporte, montos totales
        /*$activeSheet->setCellValue("H{$index}", $total_pagos);
        $activeSheet->setCellValue("I{$index}", $total_deuda);
        $activeSheet->setCellValue("J{$index}", $total);*/
        $filename = "reporte_clientes_{$this->usuario_sesion['id']}.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        return $this->response->withFile(
            $filename,
            ['download' => true, 'name' => "reporte_de_cliente_" . date("Y_m_d") . ".xlsx"]
        );
    }

    public function exportarContactos($clientes, $params = [])
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Contactos");
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Reporte de Contactos');
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
        $activeSheet->getColumnDimension('A')->setWidth(25);
        $activeSheet->getColumnDimension('B')->setWidth(10);
        $activeSheet->getColumnDimension('C')->setWidth(20);    //cargo
        $activeSheet->getColumnDimension('D')->setWidth(25);
        $activeSheet->getColumnDimension('E')->setWidth(25);
        $activeSheet->getColumnDimension('F')->setWidth(35); // correo
        $activeSheet->getColumnDimension('G')->setWidth(20);
        $activeSheet->getColumnDimension('H')->setWidth(20);
        $activeSheet->getColumnDimension('I')->setWidth(20);
        $activeSheet->getColumnDimension('J')->setWidth(20);
        $activeSheet->setCellValue("A{$index}", "Razón Social");
        $activeSheet->setCellValue("B{$index}", "Tipo");
        $activeSheet->setCellValue("C{$index}", "Cargo");
        $activeSheet->setCellValue("D{$index}", "Nombres");
        $activeSheet->setCellValue("E{$index}", "Apellidos");
        $activeSheet->setCellValue("F{$index}", "Correo");
        $activeSheet->setCellValue("G{$index}", "Telefono");
        $activeSheet->setCellValue("H{$index}", "Cel. Trabajo");
        $activeSheet->setCellValue("I{$index}", "Cel. Personal");
        $activeSheet->setCellValue("J{$index}", "Whatsapp");


        $index++; // agreamos 1 fila más
        foreach ($clientes as $v) {
            $contactos = $this->CuentaPersonas->find()->where(['cuenta_id' => (int) $v->id])->contain(['Personas']);
            $clientetipo = $this->CuentaTipos->find()->where(['id' => (int) $v->cliente_tipo_id])->first();
            $clientetipo_str = ($clientetipo) ? $clientetipo->nombre : "";
            foreach ($contactos as $cc) {

                $activeSheet->setCellValue("A{$index}", $v->nombre_comercial);
                $activeSheet->setCellValue("B{$index}", $clientetipo_str);
                $activeSheet->setCellValue("C{$index}", $cc->persona->cargo_empresa);
                $activeSheet->setCellValue("D{$index}", $cc->persona->nombres);
                $activeSheet->setCellValue("E{$index}", $cc->persona->apellidos);
                $activeSheet->setCellValue("F{$index}", $cc->persona->correo);

                $activeSheet->setCellValueExplicit(
                    "G{$index}",
                    $cc->persona->telefono,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
                $activeSheet->setCellValueExplicit(
                    "H{$index}",
                    $cc->persona->celular_trabajo,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
                $activeSheet->setCellValueExplicit(
                    "I{$index}",
                    $cc->persona->celular_personal,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
                $activeSheet->setCellValueExplicit(
                    "J{$index}",
                    $cc->persona->whatsapp,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $index++;
            }
        }
        $index++;   // agrega una fila
        // resumen del reporte, montos totales
        /*$activeSheet->setCellValue("H{$index}", $total_pagos);
        $activeSheet->setCellValue("I{$index}", $total_deuda);
        $activeSheet->setCellValue("J{$index}", $total);*/
        $filename = "reporte_contactos_al_{$this->usuario_sesion['id']}.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        return $this->response->withFile($filename, ['download' => true, 'name' => "reporte_de_contactos_al_" . date("Y_m_d") . ".xlsx"]);
    }

    /**
     * View method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function detalles($id = null)
    {
        $cliente = $this->Cuentas->get($id);

        $contactos_del_cliente = $this->Personas->find()->where(['cliente_id' => $id]);
        /*-------------------------------eventos------------------------------------- */
        $eventos_del_cliente = $this->Eventos->find()->where(['cliente_id' => $id]);

        foreach ($eventos_del_cliente as $ev) {
            $proy = $this->Proyectos->find()->where(['id' => $ev->proyecto_id])->first();
            $clie = $this->Cuentas->find()->where(['id' => $ev->cliente_id])->first();
            $coti = $this->Cotizaciones->find()->where(['id' => $ev->cotizacion_id])->first();

            $ev->proyecto_nombre = ($proy) ? $proy->nombre : "Sin Proyecto";
            $ev->cliente_nombre = ($clie) ? $clie->razon_social : "Sin Cliente";
            $ev->cotizacion_nombre = ($coti) ? $coti->proyecto_nombre : "Sin Proforma";
        }
        /*--------------------------------fin eventos---------------------------------- */
        /*-------------------------------proyectos------------------------------------- */
        $proyectos_del_cliente = $this->Proyectos->find()->where(['cuenta_id' => $id]);

        foreach ($proyectos_del_cliente as $proyecto) {
            //BUSCA TODAS LAS COTIZACIONES POR ID DE PROYECTO
            $proyecto->cotizaciones = $this->Cotizaciones->find()->where(['proyecto_id' => $proyecto->id]);
        }
        /*-------------------------------fin proyectos----------------------------------*/
        $cotizaciones_del_cliente = $this->Cotizaciones->find()->where(['cliente_id' => $id]);


        $this->paginate = [
            'limit' => 20,
        ];
        //$ventas = $this->Ventas->find()->where(['cliente_id' => $id]);
        //$ventas = $this->paginate($ventas);
        $top_links = [
            'btnBack' => [
                'tipo' => 'link',
                'nombre' => "<i class='fas fa-chevron-left fa-fw'></i>",
                'params' => [
                    'controller' => 'Cuentas',
                    'action' => 'index',
                ]

            ],
            'txtTitulo' => [
                'tipo' => 'text',
                'nombre' => "Nuevo cliente"
            ]
        ];


        $this->set("web_titulo", "Clientes");
        $this->set("top_links", $top_links);
        $this->set('cliente', $cliente);
        $this->set('_serialize', ['cliente']);
        $this->set("contactos_del_cliente", $contactos_del_cliente);
        $this->set("eventos_del_cliente", $eventos_del_cliente);
        $this->set("proyectos_del_cliente", $proyectos_del_cliente);
        $this->set("cotizaciones_del_cliente", $cotizaciones_del_cliente);
        $this->set("title", $cliente->nombre);
    }

    public function add()
    {
        $cuenta = $this->Cuentas->newEmptyEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $cuenta = $this->Cuentas->patchEntity($cuenta, $data);
            $cuenta->estado = 'ACTIVO';
            $cuenta->usuario_id = $this->usuario_sesion['id'];

            $cuenta = $this->Cuentas->save($cuenta);

            if ($cuenta) {
                $contacto = $this->guardarContactos($data, $cuenta);
                $this->Flash->success('Cliente guardado Exitosamente');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('Ha habido un error en el registro del cliente');
                return $this->redirect(['action' => 'index']);
            }
        }

        //return $this->redirect(['controller' => 'Clientes', 'action' => 'edit', $cliente->id]);


        $top_links = [
            'title' => "Registro de Nueva Empresa",
            'links' => [
                'btnBack' => [
                    'name' => "<i class='fas fa-database fa-fw'></i> Ver Empresas",
                    'params' => [
                        'controller' => 'Cuentas',
                        'action' => 'index',
                    ]
                ],
            ],
        ];

        $this->set("top_links", $top_links);
        $this->set("view_title", "Registro de Nuevo Cliente");

        $canales = $this->CuentaCanalLlegadas->find('list');
        $cliente_tipos = $this->CuentaTipos->find('list');
        $this->set("cliente_tipos", $cliente_tipos);

        $this->set("ubicaciones", $this->getUbicaciones());
        $this->set("cliente", $cuenta);
        $this->set("canales", $canales);
    }

    /**
     * Concatena la información del cliente pra agilizar las busquedas
     *
     * @param type $cliente
     * @return type
     */
    public function generarInfoBusqueda($cliente)
    {
        // concatenamos la info de la empresa, los contactos y los proyectos
        $info_busqueda = "{$cliente->ruc} {$cliente->nombre_comercial} {$cliente->razon_social}";


        /*
        $contactos = $this->Personas->find()->where(['cliente_id' => $cliente->id]);
        foreach ($contactos as $c){
            $info_busqueda .= " / {$c->nombres} {$c->apellidos} {$c->cargo_empresa}";
        }
        */

        //        $proyectos = $this->Proyectos->find()->where(['cuenta_id' => $cliente->id]);
        //        foreach ($proyectos as $c) {
        //            $info_busqueda .= " / {$c->nombre}";
        //        }

        return $info_busqueda;
    }

    public function guardarContactos($data, $cuenta)
    {
        $contactos = json_decode($data['contacto_json'], true);

        print_r($contactos);

        $def_contacto = null;

        $this->CuentaPersonas->deleteAll(['cuenta_id' => $cuenta->id]);

        foreach ($contactos as $c) {
            // buscamos el contacto
            $contacto = $this->Personas->find()->where(['id' => $c['id']])->first();

            // si no encontramos, creamos uno nuevo
            if (!$contacto) {
                $contacto = $this->Personas->newEmptyEntity();
            }

            // llenamos los datos del contacto
            $contacto->dni = $c['dni'];
            $contacto->nombres = $c['nombres'];
            $contacto->apellidos = $c['apellidos'];
            $contacto->cargo_empresa = $c['cargo_empresa'];
            $contacto->domicilio = $c['domicilio'];
            $contacto->correo = $c['correo'];
            $contacto->telefono = $c['telefono'];
            $contacto->fecha_nacimiento = $c['fecha_nacimiento'];
            $contacto->celular_trabajo = $c['celular_trabajo'];
            $contacto->celular_personal = $c['celular_personal'];
            $contacto = $this->Personas->save($contacto);

            // si existe la variable proyecto_persona_id
            // retornamos el ID de contacto que tenga el mismo valor de la variable
            if (isset($data['proyecto_persona_id'])) {
                if ($data['proyecto_persona_id'] == $c['persona_id']) {
                    $def_contacto = $contacto;
                }
            }

            // guardamos la relacion entre contacto y cuenta
            $rel = $this->CuentaPersonas->newEmptyEntity();
            $rel->cuenta_id = $cuenta->id;
            $rel->persona_id = $contacto->id;
            $rel->cargo = $c['cargo_empresa'];
            $this->CuentaPersonas->save($rel);
        }

        return $def_contacto;
    }

    private function guardarProyecto($data, $cuenta, $contacto)
    {

        if ($data['proyecto_nombre'] != '') {
            $proyecto = $this->Proyectos->newEmptyEntity();
            $proyecto->cuenta_id = $cuenta->id;
            $proyecto->nombre = $data['proyecto_nombre'];
            $proyecto->descripcion = $data['proyecto_descripcion'];
            $proyecto->persona_id = $contacto->id;
            $proyecto->estado = 'PROSP';
            $proyecto->usuario_id = $this->usuario_sesion['id'];
            $proyecto->info_busqueda = "{$proyecto->nombre} / {$cuenta->razon_social} / {$cuenta->nombre_comercial} / {$contacto->nombres} {$contacto->apellidos}";
            return $this->Proyectos->save($proyecto);
        }
        return false;
    }

    /**
     * Edit method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cuenta = $this->Cuentas->get($id, []);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();
            $cuenta = $this->Cuentas->patchEntity($cuenta, $data);
            //$cuenta->tipo_cliente=$data['tipo_cliente'];
            $cuenta->tipo_cliente = 7;

            $validaciones = $this->validarCliente($cuenta);
            if (count($validaciones) > 0) {
                $this->Flash->error(implode(",", $validaciones));
            } else {
                $this->Flash->success("El Cliente se ha modificado con éxito");
                $cuenta->estado = 'ACTIVO';
                $cuenta->fecha_creacion = date("Y-m-d H:i:s");
            }
            if ($this->Cuentas->save($cuenta)) {

                //                $cuenta->info_busqueda = $this->generarInfoBusqueda($cuenta);
                $cuenta = $this->Cuentas->save($cuenta);


                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error("Ha ocurrido un error, por favor, intente nuevamente");
            }
        }
        $top_links = [
            'title' => "Modificación Cliente",
            'links' => [
                'btnBack' => [
                    'name' => "<i class='fas fa-database fa-fw'></i> Empresas",
                    'params' => [
                        'controller' => 'Cuentas',
                        'action' => 'index',
                    ]
                ],
            ],
        ];

        $this->set("top_links", $top_links);

        $this->set("view_title", "Editar Cliente");

        $canales = $this->CuentaCanalLlegadas->find('list');
        $cliente_tipos = $this->CuentaTipos->find('list');


        $this->set("cliente_tipos", $cliente_tipos);
        $this->set("ubicaciones", $this->getUbicaciones());
        $this->set("cliente", $cuenta);
        $this->set("canales", $canales);
        $this->set('_serialize', ['cliente']);
    }

    private function validarCliente($cliente)
    {
        /**
         * Para validar seguimos lo siguiente
         * minimo 3 contactos
         * ruc ingresado y no duplicado
         */
        $validaciones = [];
        if ($this->existeRUC($cliente->ruc) && $cliente->estado == 'NUEVO') {
            $validaciones[] = "Ya existe un cliente con el mismo RUC";
        }

        if ($this->contarContactos($cliente)) {
            //$validaciones[] = "Registre por lo menos 1 contacto para éste cliente";
        }
        return $validaciones;
    }

    private function existeRUC($ruc)
    {
        $cc = $this->Cuentas->find()->where(['ruc' => $ruc, 'estado <> ' => 'NUEVO'])->first();
        return $cc;
    }

    private function contarContactos($cliente)
    {

        //        $Personas = $this->Personas->find()->where(['cliente_id' => $cliente->id]);
        //        return (count($Personas->toArray()) == 0);

        return $this->CuentaPersonas->find()->where(['cuenta_id' => $cliente->id])->count() == 0;
    }

    /**
     * Delete method
     *
     * @param string|null $id Cliente id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cliente = $this->Cuentas->get($id);
        if ($this->Cuentas->delete($cliente)) {
            $this->CuentaPersonas->deleteAll(['cuenta_id' => $id]);
            $this->Flash->success("La cuenta se ha eliminado con éxito");
        } else {
            $this->Flash->error("Ha ocurrido un error, por favor, comuníquese con soporte");
        }

        return $this->redirect(['action' => 'index']);
    }

    public function deleteAll()
    {

        $result = [
            'success'   =>  false,
            'message'   =>  'incorrect-method',
            'data'      =>  ''
        ];

        if ($this->request->is(['DELETE', 'POST'])) {

            $ids = $this->request->getData('ids');

            $cuentas = $this->Cuentas->find()->where(['id IN' => $ids]);

            $cuentaPersonas = $this->CuentaPersonas->find()->where(['CuentaPersonas.cuenta_id IN ' => $ids]);

            foreach ($cuentaPersonas as $cp) {
                $this->CuentaPersonas->delete($cp);
            }

            foreach ($cuentas as $c) {
                $this->Cuentas->delete($c);
            }


            $result = [
                'success'   =>  true,
                'message'   =>  'Eliminacion exitosa',
                'data'      =>  ""
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    public function contactoListar()
    {

        $cuenta_id = $this->request->getQuery("cliente_id");
        $rels = $this->CuentaPersonas->find()->where(['cuenta_id' => $cuenta_id]);
        $contactos = [];
        foreach ($rels as $rel) {
            $cc = $this->Personas->find()->where(['id' => $rel->persona_id])->first();
            $cc->cargo_empresa = $rel->cargo;
            $cc->rel_id = $rel->id;
            $contactos[] = $cc;
        }

        echo json_encode($contactos);
        exit;
    }

    public function contactoEliminar()
    {
        $persona_id = $this->request->getQuery("rel_id");
        $contacto_rel = $this->CuentaPersonas->get($persona_id);
        $this->CuentaPersonas->delete($contacto_rel);
        echo "ok";
        exit;
    }

    public function imprimir($id = null)
    {
        $data = $this->request->getData();
        $cant = $data['cantidad'];
        $size = $data['tamanio'];
        $tipo = $data['tipo'];
        $this->pdf($id, $cant, $size, $tipo);
    }

    public function contactoGetJson()
    {
        $id = $this->request->getQuery("rel_id");

        $rel = $this->CuentaPersonas->get($id);

        $cc = $this->Personas->find()->where(['id' => $rel->persona_id])->first();
        $cc->cargo_empresa = $rel->cargo;
        echo json_encode($cc);
        exit;
    }

    public function ajaxBuscarCoincidencias()
    {

        $busqueda = $this->request->getQuery("b");
        //echo $busqueda;exit;

        $clientes = $this->Cuentas->find()->where(['info_busqueda LIKE ' => "%{$busqueda}%"]);

        //header("Content-type: text/json; charset=UTF-8");
        echo json_encode($clientes);
        exit;
    }

    public function addTipoCliente()
    {
        $this->request->allowMethod(['get', 'put']);
        $cuenta = new CuentaTipo();
        $cuenta->nombre = $this->request->getQuery('nombre');
        $cuenta = $this->CuentaTipos->save($cuenta);
        echo json_encode($cuenta);
        exit;
    }

    public function addCanalLlegada()
    {

        $this->request->allowMethod(['get', 'put']);
        $canal = new CuentaCanalLlegada();
        $canal->nombre = $this->request->getQuery('nombre');
        $canal = $this->CuentaCanalLlegadas->save($canal);
        echo json_encode($canal);
        exit;
    }

    public function findContactoById()
    {

        $contacto = $this->Personas->get($this->request->getQuery("id"));

        echo json_encode($contacto);
        exit;
    }

    public function guardarContactoById()
    {
        $this->request->allowMethod(['put']);

        if ($this->request->getData("id")) {
            $contacto = $this->Personas->get($this->request->getData("id"));
        } else
            $contacto = $this->Personas->newEmptyEntity();
        $contacto = $this->Personas->patchEntity($contacto, $this->request->getData());
        $this->Personas->save($contacto);
        echo json_encode($contacto);
        exit;
    }

    public function findCuentaByRuc()
    {
        $contacto = $this->Cuentas->find()->where(["ruc" => $this->request->getQuery("ruc")])->first();
        if ($contacto) {
            echo json_encode($contacto);
        } else {
            echo json_encode("error");
        }
        exit;
    }

    public function saveFromArray($data, $comprobar_dni_ruc = false)
    {
        $cliente = false;

        $id = isset($data['id']) ? $data['id'] : 0;
        $cliente = $this->Cuentas->find()->where(['id' => $id])->first();

        // si no existe, entonces creamos un nuevo cliente
        if (!$cliente) {
            $cliente = $this->Cuentas->newEmptyEntity();
        }
        $cliente = $this->Cuentas->patchEntity($cliente, $data);
        $cliente->info_busqueda = "{$cliente->ruc} - {$cliente->razon_social} - {$cliente->nombre_comercial}";
        $this->Cuentas->save($cliente);

        return $cliente;
    }

    /**
     * Verifica si la persona existe en la relacion de contactos de la cuenta
     * @param $persona
     * @return mixed
     */
    public function getCuentaFromPersona($persona)
    {

        $rel = $this->CuentaPersonas->find()->where(['persona_id' => $persona->id])->first();
        if ($rel) {
            $cta = $this->Cuentas->find()->where(['id' => $rel->cuenta_id])->first();
            return $cta;
        } else {
            return false;
        }
    }

    public function apiConsultaClientes()
    {
        $q = $this->request->getQuery("query");

        $clientes = [];

        $cuentas = $this->Cuentas->find("all")->where(['info_busqueda LIKE' => "%{$q}%"]);
        foreach ($cuentas as $c) {
            $clientes[] = [
                'id' => $c['id'],
                'value' => $c['info_busqueda'],
                'cliente_doc_numero' => $c["ruc"],
                'cliente_doc_tipo' => "6",
                'cliente_razon_social' => $c['razon_social'],
                'cliente_nombre_comercial' => $c['nombre_comercial'],
                'cliente_domicilio_fiscal' => $c['domicilio_fiscal'],
            ];
        }

        $personas = $this->Personas->find("all")->where(['info_busqueda LIKE' => "%{$q}%"]);
        foreach ($personas as $c) {
            $clientes[] = [
                'id' => $c['id'],
                'value' => $c['info_busqueda'],
                'cliente_doc_numero' => $c["dni"],
                'cliente_doc_tipo' => '1',
                'cliente_razon_social' => $c['apellidos'] . " " . $c['nombres'],
                'cliente_nombre_comercial' => $c['apellidos'] . " " . $c['nombres'],
                'cliente_domicilio_fiscal' => $c['domicilio'],
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                "query" =>  $q,
                "suggestions"   =>  $clientes
            ]));
    }

    /**
     * Guarda o actualiza una cuenta
     * recibe los datos como JSON
     * @return \Cake\Http\Response|null
     */
    public function saveFromJson()
    { // saveJson
        $result = [
            'success'   =>  true,
            'message'   =>  'invalid-method',
            'data'      =>  ""
        ];

        if ($this->request->is(['post'])) {
            $data = $this->request->getData();
            $id = isset($data['id']) ? $data['id'] : 0;

            $persona = $this->Cuentas->find()->where(['id' => $id])->first();
            if (!$persona) {
                $persona = $this->Cuentas->newEntity($data);
            }

            $persona = $this->Cuentas->patchEntity($persona, $data);
            if ($this->Cuentas->save($persona)) {
                $result = [
                    'success'   =>  true,
                    'message'   =>  'oki',
                    'data'      =>  $persona
                ];
            } else {
                $result = [
                    'success'   =>  false,
                    'message'   =>  'error',
                    'data'      =>  ':('
                ];
            }
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function canales()
    {
        $cuentaCanalLlegadas = $this->paginate($this->CuentaCanalLlegadas);

        $top_links = [
            'title' => "Canales de Llegada",
            'links' =>  [
                'btnNuevo' => [
                    'name' => "<i class='fas fa-plus fa-fw'></i> Nuevo Canal de Llegada",
                    'params' => []
                ],
            ]
        ];

        $this->set('top_links', $top_links);
        $this->set('view_title', "Canales de Llegada");
        $this->set(compact('cuentaCanalLlegadas'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function tipos()
    {
        $tipos = $this->paginate($this->CuentaTipos);

        $top_links = [
            'title' => "Tipos de Cuentas",
            'links' =>  [
                'btnNuevo' => [
                    'name' => "<i class='fas fa-plus fa-fw'></i> Nuevo Tipo de Cuenta",
                    'params' => []
                ],
            ]
        ];

        $this->set('top_links', $top_links);
        $this->set('view_title', "Tipos de Cuentas");
        $this->set('items', $tipos);
    }

    public function documentos($cliente_id = "")
    {
        if ($cliente_id != "" && $cliente_id != "0") {
            $cliente = $this->Cuentas->find()->where(['Cuentas.id' => $cliente_id])->first();
            $this->set(compact('cliente'));
        }

        $opt_emisor = $this->request->getQuery('opt_emisor', '');
        $opt_cliente = $this->request->getQuery('opt_cliente', '');
        $opt_tipo_doc = $this->request->getQuery('opt_tipo_doc', '');
        $f_inicio = $this->request->getQuery('f_inicio', date("Y-m-d", strtotime("-12 month", time())));
        $f_fin = $this->request->getQuery('f_fin', date("Y-m-d"));

        $this->set(compact('opt_cliente', 'opt_emisor', 'opt_tipo_doc', 'f_inicio', 'f_fin'));

        $top_links = [
            'title'    =>  "Documentos",
            'links'    =>  [
                'btnNuevo' =>  [
                    'name' =>  "<i class='fas fa-chevron-left fa-fw'></i> Atras",
                    'params'   =>  ['controller' => 'Cuentas', 'action' => 'index']
                ],
            ],
        ];

        $this->set("top_links", $top_links);

        $ventas = $this->Ventas->find()->where([
            'cliente_id' => $cliente_id,
            'fecha_venta >= ' => $f_inicio,
            'fecha_venta <= ' => $f_fin,
        ])->order(['Ventas.id' => 'DESC']);

        $this->set(compact('ventas'));


        $this->set('view_title', 'Ventas');
    }

    public function ventas($cuenta_id = "0")
    {
        $factura = $this->request->getQuery("factura", "");
        $fini = $this->request->getQuery("fecha_ini");
        $fini = ($fini == '') ? date("Y-m-d", strtotime("-1 week", time())) : $fini;
        $ffin = $this->request->getQuery("fecha_fin");
        $ffin = ($ffin == '') ? date("Y-m-d") : $ffin;

        $exportar = $this->request->getQuery("exportar");
        $tipo_venta = $this->request->getQuery("tipo_venta");

        // nuevos filtros para el reporte del contador
        // $opt_estado = $this->request->getQuery("opt_estado");
        $opt_documento_tipo = $this->request->getQuery("opt_documento_tipo");
        $opt_nombres = $this->request->getQuery("opt_nombres", "") ?? "";

        $this->paginate = [
            'limit' => 10,
            'order' => ['fecha_venta' => 'DESC'],
        ];

        $ventas = $this->Ventas->find()->where([
            'Ventas.cliente_id' => $cuenta_id,
            'fecha_venta >= ' => $fini . " 00:00:00",
            'fecha_venta <= ' => $ffin . " 23:59:59",
        ]);

        if ($opt_nombres != "") {
            $ventas = $ventas->andWhere(['cliente_razon_social LIKE ' => "%$opt_nombres%",]);
        }

        $ventas2 = $this->Ventas->find()->where([
            'Ventas.cliente_id' => $cuenta_id,
            'fecha_venta >= ' => $fini . " 00:00:00",
            'fecha_venta <= ' => $ffin . " 23:59:59",
        ])->orderAsc('fecha_venta');

        if ($opt_nombres != "") {
            $ventas2 = $ventas2->andWhere(['cliente_razon_social LIKE ' => "%$opt_nombres%",]);
        }

        $opt_boleta = $this->request->getQuery('BOLETA', 'NONE');
        $opt_factura = $this->request->getQuery('FACTURA', 'NONE');
        $opt_notaventa = $this->request->getQuery('NOTAVENTA', 'NONE');

        if ($this->request->getQuery("fecha_ini") == "") {
            $opt_boleta = 'BOLETA';
            $opt_factura = 'FACTURA';
            $opt_notaventa = 'NOTAVENTA';
        }

        $this->set(compact('opt_boleta', 'opt_factura', 'opt_notaventa'));

        $ventas = $ventas->andWhere(['documento_tipo IN ' => [
            $opt_boleta,
            $opt_factura,
            $opt_notaventa,
        ]]);

        $ventas2 = $ventas2->andWhere(['documento_tipo IN ' => [
            $opt_boleta,
            $opt_factura,
            $opt_notaventa,
        ]]);

        $top_links = [
            'title'  =>  "Mis Ventas"
        ];
        $ventasTotales = clone $ventas;
        $ventasTotales->select([
            'sum_total' => $ventasTotales->func()->sum('Ventas.total'),
            'sum_pagos' => $ventasTotales->func()->sum('Ventas.total_pagos'),
            'sum_deuda' => $ventasTotales->func()->sum('Ventas.total_deuda'),
        ]);

        if ($exportar == 1) {
            $repo = new ReporteVentasController(new ServerRequest());
            return $repo->exportar($ventas2, [ // $ventas2
                'fecha_inicio'  =>  [
                    'label' =>  'Fec. Inicio',
                    'value' =>  $fini
                ],
                'fecha_fin'     =>  [
                    'label' =>  'Fec. Fin',
                    'value' =>  $ffin
                ]
            ]);
            exit;
        }

        if (strlen($factura) == 13) {
            $ventas = $this->Ventas->find()->where([
                'documento_serie' => substr($factura, 0, 4),
                'documento_correlativo' => substr($factura, 5),
            ]);
        }

        $ventas = $this->paginate($ventas);
        $this->set("view_title", "Ventas");
        $this->set("top_links", $top_links);
        $this->set("ventas", $ventas);
        $this->set("totales", $ventasTotales->first());
        $this->set("tipo_venta", $tipo_venta);
        $this->set("fini", $fini);
        $this->set("ffin", $ffin);
        $this->set("documento_tipos", $this->tiposdoc);
        $this->set('_serialize', ['ventas']);
        $this->set("tiposventa", $this->tiposventa);

        // valores para los filtros
        // $this->set("opt_estado" , $opt_estado);
        $this->set("opt_documento_tipo", $opt_documento_tipo);
        $this->set("opt_documento_tipos", $this->tiposdoc);

        $this->set(compact('opt_nombres'));

        $cliente = $this->Cuentas->get($cuenta_id);
        $this->set(compact('cliente'));
    }
    public function getOneCuenta($id = "0")
    {

        $respuesta =
            [
                'success' => false,
                'data' => [],
                'message' => "La cuenta no existe"
            ];


        $cuenta = $this->Cuentas->find()->where(['id' => $id])->first();

        if ($cuenta) {
            $respuesta =
                [
                    'success' => true,
                    'data' => $cuenta,
                    'message' => "La cuenta existe"
                ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }

    public function getOne($id = "0")
    {

        $respuesta =
            [
                'success' => false,
                'data' => [],
                'message' => "La cuenta no existe"
            ];


        $cuenta = $this->Cuentas->find()->where(['id' => $id])->first();

        if ($cuenta) {
            $respuesta =
                [
                    'success' => true,
                    'data' => $cuenta,
                    'message' => "La cuenta existe"
                ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }

    public function mergeContacts()
    {

        $data = $this->request->getData();
        $cuenta_origen_id = $data['id_origen'];
        $cuenta_destino_id = $data['id_destino'];

        $cuenta_origen = $this->Cuentas
            ->find()
            ->where(['Cuentas.id'   =>  $cuenta_origen_id])
            ->first();

        if (!$cuenta_origen) {
            return $this->responseWithError("La empresa de origen especificada no existe");
        }

        $cuenta_destino = $this->Cuentas
            ->find()
            ->where(['Cuentas.id'   =>  $cuenta_destino_id])
            ->first();

        if (!$cuenta_destino) {
            return $this->responseWithError("La empresa de destino especificada no existe");
        }

        $contactos_cuenta_origen = $this->CuentaPersonas
            ->find()
            ->where(['CuentaPersonas.cuenta_id'    => $cuenta_origen->id]);

        if (!$contactos_cuenta_origen->isEmpty()) {
            foreach ($contactos_cuenta_origen as $contacto) {
                $contacto->cuenta_id = $cuenta_destino->id;
                $this->CuentaPersonas->save($contacto);
            }
        }

        /********************* */

        $connection = ConnectionManager::get('default');
        $connection->transactional(function ($connection) use($cuenta_origen,$cuenta_destino) {
            /**Reemplazando datos en cotizaciones */

            $cotizaciones = $this->Cotizaciones
                ->find()
                ->where([
                    'cliente_id' => $cuenta_origen->id,
                    'cliente_doc_tipo' =>  '6'
                ]);

            if (!$cotizaciones->isEmpty()) {
                foreach ($cotizaciones as $coti) {
                    $coti->cliente_id = $cuenta_destino->id;
                    $coti->cliente_doc_numero = $cuenta_destino->ruc;
                    $coti->cliente_razon_social = $cuenta_destino->razon_social;
                    $coti->cliente_domicilio_fiscal = $cuenta_destino->domicilio_fiscal;

                    $this->Cotizaciones->save($coti);
                }
            }


            /***************************** */
            /**Reemplazando datos en ventas */
            $ventas = $this->Ventas
                ->find()
                ->where([
                    "cliente_id"    =>  $cuenta_origen->id,
                    "cliente_doc_tipo"  =>  '6'
                ]);

            if (!$ventas->isEmpty()) {
                foreach ($ventas as $venta) {
                    $venta->cliente_id = $cuenta_destino->id;
                    $venta->cliente_doc_numero = $cuenta_destino->ruc;
                    $venta->cliente_razon_social = $cuenta_destino->razon_social;
                    $venta->cliente_domicilio_fiscal = $cuenta_destino->domicilio_fiscal;

                    $this->Ventas->save($venta);
                }
            }

            /************* Eliminamos la cuenta anterior */
                if(!$this->Cuentas->delete($cuenta_origen)){
                    throw new \Exception('No se elimino la cuenta');
                }
            /**************** */

        });



        return $this->responseWithJson($cuenta_destino, "Datos unificados con éxito");
    }
}
