<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\AppController;
use App\Model\Entity\SunatFeEmisor;
use Cake\Controller\Controller;
use Cake\I18n\Number;
use Endroid\QrCode\QrCode;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Utility\Inflector;
use Cake\Http\ServerRequest;

/**
 * Ventas Controller
 *
 * @property \App\Model\Table\VentasTable $Ventas
 * @method \App\Model\Entity\Venta[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VentasController extends AppController
{

    var $tiposventa = [
        '1'   =>  'Contado',
        '2'   =>  'Crédito'
    ];

    var $errores_comunes_sunat = [
        'CDR' =>  'Ha ocurrido un error, se recomienda dar click en el boton de verificacion con SUNAT'  ,
        'HTTP'      =>  "Servidor de SUNAT Ocupado, intente enviar el comprobante nuevamente en unas horas",
        '0130'      =>  "Servidor de SUNAT Ocupado, intente enviar el comprobante nuevamente en unas horas",
        '0109'      =>  "Servidor de SUNAT Ocupado, intente enviar el comprobante nuevamente en unas horas",
        '1033'  =>  "Ha ocurrido un error, se recomienda dar click en el boton de verificacion con SUNAT",
    ];

    var $codigos_detracciones =
        [
            //https://cpe.sunat.gob.pe/sites/default/files/inline-files/anexoV-340-2017.pdf
            //https://orientacion.sunat.gob.pe/sites/default/files/inline-files/Cartilla_detracciones.pdf
            //Bienes sujetos al sistema
//                    '001'=>'Azúcar',
//                    '003'=>'Alcohol Etílico',
            //Bienes
//                    '004'=>'Recursos hidrobiológicos',
//                    '005'=>'Maíz Amarrillo duro',
//                    '006'=>'Algodón',
//                    '007'=>'Caña de Azúcar',
//                    '008'=>'Madera',
//                    '009'=>'Arena y piedra',
//                    '010'=>'Residuos y subproductos, desechos, recortes y desperdicios',
//                    '040'=>'Bien inmueble gravado con IGV',
//                    '014'=>'Carnes y despojos comestibles',
//                    '016'=>'Aceite de pescado',
//                    '017'=>'Harina, polvo y “pellets” de pescado, crustáceos, moluscos y demás invertebrados acuáticos',
//                    '023'=>'Leche',
//                    '031'=>'Oro gravado con el IGV',
//                    '032'=>'Páprika y otros frutos de los géneros capsicum o pimienta',
//                    '034'=>'Minerales metálicos no auríferos',
//                    '035'=>'Bienes exonerados del IGV',
//                    '036'=>'Oro y demás minerales metálicos exonerados del IGV',
//                    '039'=>'Minerales no metálicos',
                    //Falta plomo
                    //No se a cual aplica ---> '011'=>'Bienes del inciso A) del Apéndice I de la Ley del IGV',

            //Servicios
                    '012'=>'Intermediación laboral y tercerización',
                    '019'=>'Arrendamiento de bienes muebles',
                    '020'=>'Mantenimiento y reparación de bienes muebles',
                    '021'=>'Movimiento de carga',
                    '022'=>'Otros servicios empresariales',
                    '024'=>'Comisión mercantil',
                    '025'=>'Fabricación de bienes por encargo',
//                    '026'=>'Servicio de transporte de personas',
                    '030'=>'Contratos de construcción',
                    '037'=>'Demás servicios gravados con el IGV',

            //No estoy seguro a cual aplican
//                     '013'=>'Animales vivos',
//                    '015'=>'Abonos, cueros y pieles de origen animal ',
//                    '018'=>'Embarcaciones pesqueras',
//                    '029'=>'Algodón en rama sin desmontar',
//                    '033'=>'Espárragos',
        ];

    var $codigos_detracciones_porcentaje =
        [
            //Bienes sujetos al sistema
//            '001'=> 10,
//            '003'=> 10,
            //Bienes
//            '004'=> 4, //Recursos hidrobiologicos
//            '005'=> 4, //Maiz amarillo duro
            //'006'=> 'Algodón',
//            '007'=> 10,//'Caña de Azúcar'
//            '008'=> 4 , //'Madera',
//            '009'=> 10,//'Arena y piedra',
//            '010'=> 15,//'Residuos y subproductos, desechos, recortes y desperdicios',
//            '040'=> 10,//'Bien inmueble gravado con IGV',
//            '014'=> 4,//'Carnes y despojos comestibles',
//            '016'=> 10,//'Aceite de pescado',
//            '017'=> 4,//'Harina, polvo y “pellets” de pescado, crustáceos, moluscos y demás invertebrados acuáticos',
//            '023'=> 4,//'Leche',
//            '031'=> 10,//'Oro gravado con el IGV',
//            '032'=> 10,//'Páprika y otros frutos de los géneros capsicum o pimienta',
//            '034'=> 10,//'Minerales metálicos no auríferos',
//            '035'=> 1.5,//'Bienes exonerados del IGV',
//            '036'=> 1.5,//'Oro y demás minerales metálicos exonerados del IGV',
//            '039'=> 10,//'Minerales no metálicos',
            //Falta plomo
            //No se a cual aplica ---> '011'=>'Bienes del inciso A) del Apéndice I de la Ley del IGV',

            //Servicios
            '012'=> 12,//'Intermediación laboral y tercerización',
            '019'=> 10,//'Arrendamiento de bienes muebles',
            '020'=> 12,//'Mantenimiento y reparación de bienes muebles',
            '021'=> 10,//'Movimiento de carga',
            '022'=> 12,//'Otros servicios empresariales',
            '024'=> 10,//'Comisión mercantil',
            '025'=> 10,//'Fabricación de bienes por encargo',
            //'026'=> 10,//'Servicio de transporte de personas',
            '030'=> 4,//'Contratos de construcción',
            '037'=> 12,//'Demás servicios gravados con el IGV',

            //No estoy seguro a cual aplican
//            '013'=>'Animales vivos',
//            '015'=>'Abonos, cueros y pieles de origen animal ',
//            '018'=>'Embarcaciones pesqueras',
//            '029'=>'Algodón en rama sin desmontar',
//            '033'=>'Espárragos',
        ];


    private $emisoresCtrl = null;

    protected $Cuentas = null;
    protected $Personas = null;
    protected $Items = null;
    protected $ItemRels = null;
    protected $Usuarios = null;
    protected $VentaRegistros = null;
    protected $VentaPagos = null;

    public function initialize(): void {
        parent::initialize();
        $this->Cuentas = $this->fetchTable('Cuentas');
        $this->Personas = $this->fetchTable('Personas');
        $this->Items = $this->fetchTable('Items');
        $this->ItemRels = $this->fetchTable('ItemRels');
        $this->Usuarios = $this->fetchTable('Usuarios');
        $this->VentaRegistros = $this->fetchTable('VentaRegistros');
        $this->VentaPagos = $this->fetchTable('VentaPagos');

        $this->loadComponent("MontosManager");
    }

    public function beforeRender(\Cake\Event\EventInterface $event) {
        parent::beforeRender($event);

    }

    public function enviarComprobante($venta, $cliente = null, $correo = '') {

        if ($correo != ''){
            $emailCtrl = new EmailController(new ServerRequest());
            $emailCtrl->enviarComprobante($venta, $cliente, $correo);
        }

        return true;
    }


    public function getDeudas($usuario_id = "0"){

        $ventas = $this->Ventas->find()->where([
            'Ventas.estado'    =>  'ACTIVO',
            'usuario_id' => $usuario_id,
            'total_deuda > ' => 0,
        ]);

        $respusta = [
            'success' => true,
            'data' => $ventas,
            'message' => "Busqueda satisfactoria"
        ];

        return $this->response->withType('application/json')->withStringBody(json_encode($respusta));

    }

    public function detalles($vid){
        $venta = $this->Ventas->get($vid, [
            'contain' => ['VentaRegistros']
        ]);
        $documento_id = "{$venta->id}";

        $top_links = [
            'title'  =>  "Detalles de la Venta $documento_id",
            'links' =>[
                // 'btnDescargar'   =>[
                //     'name'  =>  "<i class='fa fa-download fa-fw'></i> Descargar o Enviar",
                //     'params'    =>  []
                // ],

                //http://localhost/gfast_v1_base/intranet/sunat-fe-facturas/descargar-pdf/2526/a4
            ],
        ];



        $all_items = $this->Items->find();
        foreach ($all_items as $it) {
            $it->value = $it->nombre;
        }
        $this->set(compact('all_items'));

        $this->set("venta", $venta);
        $this->set("top_links", $top_links);
        $this->set("documento_id", $documento_id);
        $this->set("view_title", "Detalles de $documento_id");
    }

    public function apiDetalles($vid){
        $venta = $this->Ventas->get($vid, [
            'contain' => ['VentaRegistros']
        ]);
        $documento_id = "{$venta->documento_serie}-{$venta->documento_correlativo}";

        $emisoresCtrl = new SunatFeEmisoresController(new ServerRequest());
        $emisor = $emisoresCtrl->getEmisor();

        $all_items = $this->Items->find();
        foreach ($all_items as $it) {
            $it->value = $it->nombre;
        }
        $this->set(compact('all_items'));

        $this->set("venta", $venta);
        $this->set("emisor", $emisor);
        $this->set("documento_id", $documento_id);

        $respuesta = [
            'success' => true,
            'data' => [
                'venta' => $venta,
                'emisor' => $emisor,
                'documento_id' => $documento_id
            ],
            'message' => "Busqueda exitosa",
        ];

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }

    /**
     * Mis ventas
     * @return mixed
     */
    public function index(){
        $factura = $this->request->getQuery("factura", "");
        $fini = $this->request->getQuery("fecha_ini");
        $fini = ($fini == '') ? date("Y-m-d", strtotime("-1 week", time())) : $fini;
        $ffin = $this->request->getQuery("fecha_fin");
        $ffin = ($ffin == '') ? date("Y-m-d"): $ffin;

        $opt_estado_pago = $this->request->getQuery("opt_estado_pago");

        $exportar = $this->request->getQuery("exportar");
        $tipo_venta = $this->request->getQuery("tipo_venta");

        // nuevos filtros para el reporte del contador
        // $opt_estado = $this->request->getQuery("opt_estado");
        $opt_documento_tipo = $this->request->getQuery("opt_documento_tipo");
        $opt_nombres = $this->request->getQuery("opt_nombres", "") ?? "";

        $ventas = $this->Ventas->find()->where([
            'Ventas.usuario_id' => $this->usuario_sesion['id'],
            'Ventas.fecha_venta >= ' => $fini . " 00:00:00",
            'Ventas.fecha_venta <= ' => $ffin . " 23:59:59"
        ]);



        if ($opt_nombres != "") {
            $ventas = $ventas->andWhere(['Ventas.cliente_razon_social LIKE ' => "%$opt_nombres%",]);
        }

        $ventas2 = $this->Ventas->find()->where([
            'usuario_id' => $this->usuario_sesion['id'],
            'fecha_venta >= ' => $fini . " 00:00:00",
            'fecha_venta <= ' => $ffin . " 23:59:59"
        ])->orderByAsc('fecha_venta');

        if ($opt_nombres != "") {
            $ventas2 = $ventas2->andWhere(['Ventas.cliente_razon_social LIKE ' => "%$opt_nombres%",]);
        }
        if (isset($opt_estado_pago) &&  $opt_estado_pago != "") {
            if($opt_estado_pago == 'DEUDA'){
                $ventas = $ventas->andWhere([
                    'Ventas.total_deuda >' => 0
                ]);
            }else if($opt_estado_pago == 'PAGADO'){
                $ventas = $ventas->andWhere([
                    'Ventas.total_deuda' => 0
                ]);

            }
        }
        /*
        if ($opt_estado != ''){
            $ventas = $ventas->andWhere(['estado' => $opt_estado]);
        }*/
//        if ($opt_documento_tipo != ''){
//            $ventas = $ventas->andWhere(['documento_tipo' => $opt_documento_tipo]);
//            $ventas2 = $ventas2->andWhere(['documento_tipo' => $opt_documento_tipo]);
//        }

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


        $top_links = [];
        $top_links['title'] = "Mis Ventas";

        $top_links['links'] = [];

        $top_links['links'] = [
            'btnNuevo'  =>  [
                'name'  =>  "<i class='fas fa-plus fa-fw'></i> Nueva Venta",
                'params'    =>  ['action' => 'nuevaVenta']
            ]
        ];
        $ventasTotales = clone $ventas;
        $ventasTotales = $ventasTotales->andWhere(['estado !=' => 'ANULADO']);

        /**
         * @author Ali Reyes
         * Calculo de ventas
         */
        $totales_ventas = [
            'pen_total' => 0,
            'pen_pagos' => 0,
            'pen_deuda' => 0,
            'usd_total' => 0,
            'usd_pagos' => 0,
            'usd_deuda' => 0,
        ];

        foreach($ventasTotales as $venta)
        {
            if($venta->tipo_moneda === 'USD')
            {
                $totales_ventas['usd_total']    += $venta->total;
                $totales_ventas['usd_pagos']    += $venta->total_pagos;
                $totales_ventas['usd_deuda']    +=   $venta->total_deuda;

            }else{

                $totales_ventas['pen_total']    += $venta->total;
                $totales_ventas['pen_pagos']    += $venta->total_pagos;
                $totales_ventas['pen_deuda']    +=   $venta->total_deuda;

            }

        }

        $this->set(compact('totales_ventas'));

        /**
         *
         */


        if ( $exportar == 1){
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
            exit();
        }

        if (strlen($factura) == 13) {
            $ventas = $this->Ventas->find()->where([
                'documento_serie' => substr($factura, 0, 4),
                'documento_correlativo' => substr($factura, 5),
            ]);
        }

        $ventas->limit(10)->orderBy(['Ventas.fecha_venta' => 'DESC']);

        $ventas = $this->paginate($ventas);
        $this->set("view_title", "Ventas");
        $this->set("top_links", $top_links);
        $this->set("ventas", $ventas);
        //$this->set("totales", $ventasTotales->first());
        $this->set("tipo_venta", $tipo_venta);
        $this->set("fini", $fini);
        $this->set("ffin", $ffin);
        $this->set('_serialize', ['ventas']);
        $this->set("tiposventa", $this->tiposventa);

        // valores para los filtros
        $this->set("opt_estado_pago" , $opt_estado_pago);

        $this->set(compact('opt_nombres'));
        $this->set("errores_sunat",$this->errores_comunes_sunat);

        $this->set("allow_valcpe_ver",$this->getConfig('allow_valcpe_ver'));

    }


    /**
     * Anula una nota de venta, establece el estado ANULADO
     * luego reestablece el inventario de la venta
     * @return \Cake\Http\Response|null
     */
    public function apiAnularNotaVenta()
    {

        $result = [
            'success'  =>  false,
            'data'      =>  'incorrect-method',
            'message'   =>  'error'
        ];

        /*
         * Al eliminar una venta
         * - se cambia el estado a E para su revision, solo el administrador puede eliminarla permanentemente
         * - se restaura el stock de los registros
         */
        if ($this->getRequest()->is(['post', 'delete'])){

            $data = $this->request->getData();
            $venta = $this->Ventas->get($data['venta_id']);
            $venta->estado = 'ANULADO';
            $venta->fecha_poranular = date("Y-m-d");
            $venta->fecha_anulacion = date("Y-m-d");
            $venta->motivo_anulacion = $data['motivo'];

            $venta_items = $this->VentaRegistros->find()->where(['venta_id'=>$venta->id]);



            if ($this->Ventas->Save($venta)) {
                $result = [
                    'success'  =>  true,
                    'data'      =>  'Venta eliminada',
                    'message'   =>  'ok',
                ];
            } else {
                $result = [
                    'success'  =>  false,
                    'data'      =>  'Ha ocurrido un error',
                    'message'   =>  'error',
                ];
            }
        }

        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($result));

    }





    public function modificarFromNotaCredito($id = null, $motivo = '')
    {
        $venta = $this->Ventas->get($id);
        $ventaid = $venta->id;
        $venta->estado = 'MODIFICADO';
        //        $venta->fecha_poranular = date("Y-m-d");
        //        $venta->fecha_anulacion = date("Y-m-d");
        $venta->motivo_anulacion = $motivo;

        // eliminamos la venta
        if ($this->Ventas->save($venta)) {
            return true;
        }
        return false;
    }
    public function modificarFromNotaDebito($id = null)
    {
        $venta = $this->Ventas->get($id);
        $venta->estado = 'MODIFICADO';


        if ($this->Ventas->save($venta)) {
            return true;
        }
        return false;
    }


    /**
     * Esta funcion elimina permanentemente una venta de la db y sus registros
     * esta funcion solo deberia ser accesible por el super administrador
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function eliminarPermanentemente($id = null)
    {

        $response = [
            'success'   => false,
            'message'   =>  'incorrect-method',
            'data'      =>  ''
        ];

        if ($this->request->is(['post', 'delete'])){
            $venta = $this->Ventas->get($id);
            $ventaid = $venta->id;

            // eliminamos la venta
            if ($this->Ventas->delete($venta)) {

                $this->VentaPagos->deleteAll(['venta_id' => $ventaid]);
                $this->VentaRegistros->deleteAll(['venta_id' => $ventaid]);

                $response['success'] = true;
                $response['message'] = 'success';
                $response['data'] = '';
            }
        }


        return $this->response
            ->withType("application/json")
            ->withStringBody(json_encode($response));
    }



    public function nuevoPago($venta_id){
        $venta = $this->Ventas->find("all")->contain(['VentaPagos'])->where(["id" => $venta_id])->first();
        if($this->request->is("post")){
            $pago = $this->VentaPagos->newEntity();
            $pago->venta_id    = $this->request->getData('venta_id');
            $pago->monto        = $this->request->getData('monto');
            $pago->nota         = $this->request->getData('nota');
            $pago->fecha_pago   = $this->request->getData('fecha_pago');
            $this->VentaPagos->Save($pago);

            $venta->total_pagos += $pago->monto;
            $venta->total_deuda = $venta->total - $venta->total_pagos;
            $this->Ventas->Save($venta);

            return $this->redirect(['action'=>'index']);
        }

        $this->set("title", "Nuevo Pago");
        $this->set("venta", $venta);
    }

    /**
     * Api para nueva implementacion de javascript en el
     * modulo de ventas
     **/

    public function nuevaVenta()
    {
        $coti_id = $this->request->getQuery('coti_id', '');
        $ntventa_id = $this->request->getQuery('ntventa_id', '');
        $this->set(compact('coti_id','ntventa_id'));

        if($this->request->is('post')){
            $data = $this->request->getData();
            try{

                $venta = $this->registrarVenta($data);
               
                if($venta){
                    // $this->generarNotaVentaPdfA4($venta->id);
                    // $this->generarNotaVentaPdfTicket($venta->id);

                    $item = $this->Ventas->find()->where(['id' => $venta->id])->contain(['VentaRegistros' , 'VentaPagos'])->first();
                    $item->fecha_venta = $item->fecha_venta->format("Y-m-d H:i:s");
                    $item->fecha_vencimiento = $item->fecha_vencimiento->format("Y-m-d H:i:s");

                    $respuesta =
                    [
                        'success'   =>  true,
                        'data'      =>  $item,
                        'documento_tipo'    =>  $item->documento_tipo,
                        'imprimir'      =>  isset($data['imprimir']) ? $data['imprimir'] : "0",
                        'message'   =>  'Registro Exitoso'
                    ];
                }else{
                    $respuesta =
                        [
                            'success'   =>  false,
                            'data'      =>  'check-message',
                            'message'   =>  'Ha ocurrido un error, por favor, intente nuevamente'
                        ];
                }
            }catch(\Throwable $e)
            {
                $respuesta = [
                    'success' => false,
                    'data' => '',
                    'message' => 'ERROR: ' . $e->getMessage(),
                ];
            }



            return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode($respuesta));


        }


        $usuarios = $this->Usuarios->find("list");

        // links que se muestra en la vista
        $top_links = [
            'title'  =>  "Nueva Venta",
            'links' =>  [
                'btnBack'  =>  [
                    'name'    =>     "<i class='fas fa-database fa-fw'></i> Ventas",
                    'params'    =>     [
                        'controller' =>  'Ventas',
                        'action'     =>  'index',
                    ]

                ],
            ]
        ];

        $servicios = $this->Items->find()->select(['id', 'codigo', 'nombre', 'precio_venta'])->where(["unidad"=>"ZZ"]);
        $this->set(compact('servicios'));

        $this->set("ven_autosavecliente", $this->getConfig("ven_autosavecliente",'0'));
        $this->set("tipodoc_defecto", $this->getConfig("ven_docdefault"));
        $this->set("usuarios", $usuarios);
        $this->set("view_title", "Nueva Venta");
        $this->set("top_links", $top_links);
        $this->set("codigo_detracciones",$this->codigos_detracciones);
        $this->set("porcentajes_detracciones",$this->codigos_detracciones_porcentaje);
        $this->set("editar_fecha_emision", $this->getConfig('editar_fecha_emision','0'));
        $this->set("metodos_pago",$this->metodos_pago);
        $this->set("establecimiento_default", $this->usuario_sesion['establecimiento_id']);

    }

    public function procesarNotaVenta($venta_id){
        $this->set("view_title", "Procesar Nota de Venta");
        $this->set('venta_id',$venta_id);
    }



    /**
     * Crea un registro de venta, si el modulo de facturación está activo
     * tambien genera los documentos en formato XML listos para su envío
     * @param $data Parámetros de la Venta
     * @return mixed Retorna un objeto Venta
     */
    private function registrarVenta($data){

        $venta = $this->Ventas->newEmptyEntity();
        $data['fecha_venta'] = $data['fecha_venta'] . " " . $data['hora_venta'];

        $venta = $this->Ventas->patchEntity($venta, $data);

        if($venta->cliente_doc_numero == "" )
        {
            $venta->cliente_doc_numero = '99999999';
            $venta->cliente_doc_tipo ='1';
        }
        if($venta->cliente_razon_social == "")
        {
            $venta->cliente_razon_social =  'CLIENTE VARIOS';
        }

        $venta->documento_tipo = $data['documento_tipo'];
        $venta->documento_serie = $data['documento_serie'];

        if($venta->documento_tipo == 'NOTAVENTA'){
            // obtenemos el correlativo y guardamos
            $venta->documento_serie = 'NV';
            $venta->documento_correlativo = str_pad("" . $this->getConfig("ven_cornondoc"), 8,"0",0);
            $correl = $this->getConfig("ven_cornondoc");
            $this->setConfig("ven_cornondoc", $correl + 1);

        }
       

        if($venta->forma_pago === "CREDITO")
        {
            $venta->total_pagos = 0;
            $venta->total_deuda = $data['monto_credito_total'];
            $venta->monto_credito = $data['monto_credito_total'];
        }else
        {
            $venta->total_pagos = $data['pago_inicial'];
        }


        $venta->emisor_id = 0;

        $venta->total_deuda    = ($venta->total - $venta->total_pagos);

        /**
         * Implementacion detracciones
         */
        $venta->tipo_operacion = isset($data['tipo_detraccion_codigo']) &&  intval($data['tipo_detraccion_codigo'])!= 0 ? '1001' : '0101';
        $venta->cod_detraccion = isset($data['tipo_detraccion_codigo']) &&
                                intval($data['tipo_detraccion_codigo'])!= 0 ? $data['tipo_detraccion_codigo'] : 0;
        $venta->porcentaje_detraccion = isset($this->codigos_detracciones_porcentaje[$venta->cod_detraccion])
                                    ? $this->codigos_detracciones_porcentaje[$venta->cod_detraccion]:
                                    0;
        $venta->monto_detraccion = $venta->porcentaje_detraccion != 0 ? $venta->total * ($venta->porcentaje_detraccion / 100) : 0;

        /**
         * Fin implementacion detracciones
         */


        /**
         * @author Ali Reyes
         */
        // calculando el IGV
        $venta->igv_percent     = 0.18;
//        $venta->subtotal        = $venta->total / (1 + $venta->igv_percent);
        $venta->subtotal        = $data['subtotal'];
        $venta->igv_monto       = $data['igv'];//$venta->subtotal * $venta->igv_percent

        $venta->op_gratuitas    = 0;



        $resultado = $this->Ventas->Save($venta);

        if($resultado){
            if($resultado->forma_pago === "CREDITO")
            {
                $this->registrarPagos($data["forma_pago_cuotas"],$venta->id);
            }else{
                $medio_pago_form = $data['medio_pago_venta'] ?? 'EFECTIVO';
                $medio_pago_venta  = $medio_pago_form == 'OTRO' ? ($data['medio_pago_otro'] ?? 'EFECTIVO') : $medio_pago_form;
                $this->registrarPagoInicial($venta->id, $data['pago_inicial'], $data['fecha_venta'], $medio_pago_venta);
            }

            // crea los registros de venta y a su vez descuenta el stock
            $venta->total_items = $this->crearVentaRegistros($venta, $data['registros']);

            // guardamos la venta
            $venta = $this->Ventas->Save($venta);

        }
        return $venta;
    }

    /**
     * Registra el pago inicial de la venta
     * @param type $venta_id
     * @param type $monto
     * @param type $fecha
     */
    private function registrarPagoInicial($venta_id, $monto, $fecha,$medio_pago = 'EFECTIVO'){

        $pago = $this->VentaPagos->newEmptyEntity();
        $pago->venta_id = $venta_id;
        $pago->monto = $monto;
        $pago->fecha_pago = $fecha;
        $pago->nota = "Pago inicial de la compra";
        $pago->medio_pago = $medio_pago;
        $this->VentaPagos->Save($pago);
    }
    private function registrarPagos($pagos,$venta_id)
    {
        $pagos_array = json_decode($pagos,true);
        $cantidad_pagos = 0;
        foreach($pagos_array as $pago)
        {
            $cantidad_pagos++;
            $pagoObj = $this->VentaPagos->newEmptyEntity();
            $pagoObj->venta_id = $venta_id ;
            $pagoObj->monto = $pago['monto'];
            $pagoObj->fecha_pago = $pago['fecha'];
            $pagoObj->estado = "PROGRAMADO" ;
            $pagoObj->nota = "Venta {$venta_id} Cuota {$cantidad_pagos}";
            $this->VentaPagos->Save($pagoObj);
        }
    }

    private function crearVentaRegistros($venta, $items){
        $items_array = json_decode($items, true);
        $index = 0;
        foreach($items_array as $item){
            if($item['item_nombre'] != ''){

                $index++;

                $itemObj = $this->Items->find()->where(['codigo' => $item['item_codigo'] ])->first();

                $reg = $this->VentaRegistros->newEmptyEntity();
                //$reg = $this->VentaRegistros->patchEntity($reg,$item);
                $reg->venta_id = $venta->id;
                $reg->item_index = $item['item_index'];
                $reg->item_id = ($item['item_id'] != '') ? $item['item_id'] : 0;
                $reg->cantidad = ($item['cantidad'] != '') ? $item['cantidad'] : 0;
                $reg->item_nombre = $item['item_nombre'];
                $reg->item_codigo = $item['item_codigo'];
                $reg->item_unidad = $item['item_unidad'];
                $reg->item_comentario = isset($item['item_comentario']) ? $item['item_comentario']: "" ;

                $reg->afectacion_igv    = $item['afectacion_igv'];

                $reg->precio_uventa     =   $item['precio_uventa'];
                $reg->valor_venta       =     $item['valor_venta'];


                $reg->subtotal          = floatval( $item['subtotal']);
                $reg->igv_monto         = floatval($item['igv_monto'])   ;
                $reg->precio_total      = floatval( $item['precio_total']);

                $this->VentaRegistros->save($reg);
            }
        }
        return  $index;
    }

    /**
     * Si el cliente no existe entonces se crea uno nuevo
     * segun el tipo de documento evaluamos si creamos una persona natural
     * o si creamos una cuenta de empresa
     *
     * @param array $data
     * @return array
     */
    private function setCliente($data){

        // segun el tipo de documento creamos una cuenta o una persona
        if($data['documento_tipo'] == 'BOLETA'){
            // si es nuevo, es decir, nunca se ha ingresado
            if ($data['persona_id'] == 'DEFAULT'){
                $cid = $this->getConfig("ven_clidefbol_id");
                $cliente = $this->Personas->get($cid);

                return [
                    'id'    =>  $cliente->id,
                    'dniruc' => $cliente->dni,
                    'razon_social' => $cliente->nombres . " " . $cliente->apellidos,
                    'tipo'      =>  'NATURAL',
                    'domicilio_fiscal' => "",
                ];
            }else {
                // si no es el valor por defecto, buscamos una entidad, si no existe la creamos
                $p = $this->Personas->find()->where(['id' => $data['persona_id'] ])->first();
                if (!$p){
                    $p = $this->Personas->newEntity();
                }
                $p->dni = $data['cliente_ruc'];
                $p->nombres = $data['cliente_razon_social'];
                $p->direccion = $data['cliente_domicilio_fiscal'];
                $p = $this->Personas->save($p);
                return [
                    'id'    =>  $p->id,
                    'dniruc' => $p->dni,
                    'razon_social' => $p->nombres,
                    'tipo'      =>  'NATURAL',
                    'domicilio_fiscal' => "",
                ];

            }
        }else{
            $cliente = $this->Cuentas->newEmptyEntity();
            if ($data['cuenta_id'] != 'DEFAULT'){
                $cliente = $this->Cuentas->find()->where(['id' => $data['cuenta_id'] ])->first();
            }
            $cliente->ruc = $data['cliente_ruc'];
            $cliente->razon_social = $data['cliente_razon_social'];
            $cliente->domicilio_fiscal = $data['cliente_domicilio_fiscal'];

            $cliente = $this->Cuentas->save($cliente);

            return [
                'id'    =>  $cliente->id,
                'dniruc' => $cliente->ruc,
                'razon_social' => $cliente->razon_social,
                'tipo'      =>  'JURIDICA',
                'domicilio_fiscal' => $cliente->domicilio_fiscal,
            ];
        }
    }

    public function ventaServicios() {

        if($this->request->is('post')){
            $data = $this->request->getData();

            // si la accion es venta, entonces procedemos
            if ($data['accion'] == 'venta'){

                $venta = $this->registrarVenta($data);

                if($venta){

                    // si es una nota de venta, entonces redireccionamos al listado
                    if ($venta->documento_tipo == 'NOTAVENTA'){
                        // obtenemos el correlativo y guardamos
                        $venta->documento_serie = 'NV';
                        $venta->documento_correlativo = str_pad($this->getConfig("ven_cornondoc"), 8,"0",0);

                        // guardamos la venta
                        $this->Ventas->save($venta);

                        $correl = $this->getConfig("ven_cornondoc");
                        $this->setConfig("ven_cornondoc", $correl + 1);

                        return $this->redirect(['action' => 'index']);
                    }

                    /*
                     * Redireccionamos a la funcion NuevaVentaAfterSave
                     * en esta funcion generamos el PDF y enviamos al correo
                     * en caso de facturación electrónica, enviamos los comprobantes a sunat
                     */
                    //return $this->redirect(['action' => 'misVentas']);

                }else{
                    $this->Flash->error("Ha ocurrido un error, por favor, intente nuevamente");
                    return $this->redirect(['action' => 'nuevaVenta']);
                }
            }

            // si la accion es cotización
            else if ($data['accion'] == 'coti'){
                $cotiController = new CotizacionesController(new ServerRequest());
                $coti = $cotiController->registrarCotizacionDesdeVenta($data);

                if($coti){
                    $this->Flash->success("La Proforma se ha registrado con éxito");
                    return $this->redirect(['controller' => 'Cotizaciones',  'action' => 'index']);
                }else{
                    $this->Flash->error("Ha ocurrido un error, por favor, intente nuevamente");
                    return $this->redirect(['action' => 'nuevaVenta']);
                }

            }
        }


        $usuarios = $this->Usuarios->find("list");

        // links que se muestra en la vista
        $top_links = [
            'title'  =>  "Nueva Venta",
            'links' =>  [
                'btnBack'  =>  [
                    'name'    =>     "<i class='fas fa-chevron-left fa-fw'></i> Mis Ventas",
                    'params'    =>     [
                        'controller' =>  'Ventas',
                        'action'     =>  'index',
                    ]

                ],
            ]
        ];

        $this->set("tipodoc_defecto", $this->getConfig("ven_docdefault"));
        $this->set("usuarios", $usuarios);
        $this->set("view_title", "Nueva Venta");
        $this->set("top_links", $top_links);


    }

    public function getOne($id = "0") {
        try {
            $item = $this->Ventas->find()->where(['id' => $id])->contain(['VentaRegistros' , 'VentaPagos'])->first();
            $item->fecha_venta = $item->fecha_venta->format("Y-m-d H:i:s");
            $item->fecha_vencimiento = $item->fecha_vencimiento->format("Y-m-d H:i:s");
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
                $item = $this->Ventas->newEmptyEntity();
                $item = $this->Ventas->patchEntity($item, $data);
                $item->id = $data['id'];
                $item = $this->Ventas->save($item);

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

    public function getOneVentaRegistros($id = "") {
        try {
            $item = $this->VentaRegistros->find()->where(['id' => $id])->first();
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

    public function getOneVentaReg($venta_id)
    {
        try {
            $item = $this->Ventas
                        ->find()
                        ->where(['id' => $venta_id])
                        ->contain(
                            [
                                'VentaRegistros'
                            ]
                        )
                        ->first();
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


    public function saveVentaRegistros() {
        try {
            if ($this->request->is('POST')) {
                $data = $this->request->getData();
                $item = $this->VentaRegistros->newEmptyEntity();
                $item = $this->VentaRegistros->patchEntity($item, $data);
                $item->id = $data['id'];
                $item = $this->VentaRegistros->save($item);

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

    public function regenerarPdfs($venta_id){
        $this->generarNotaVentaPdfA4($venta_id);
        $this->generarNotaVentaPdfTicket($venta_id);

        $this->Flash->success("Los documentos para impresion para esta venta han sido regenerados");
        return $this->redirect(['controller' => 'Ventas', 'action' => "detalles", $venta_id]);
    }


    // corregir en donde aparezca
    public function generarNotaVentaPdfA4($venta_id) {

        $venta = $this->Ventas->get($venta_id, ['contain' => 'VentaRegistros']);

        $logo =  WWW_ROOT. $this->getConfig("global_logo");

        $usuario = $this->Usuarios->find()->where(['id' => $venta->usuario_id])->first();


        $pagos  =   $this->VentaPagos->find()->where(['venta_id'=>$venta->id])->toArray();


        // TODO implementar una variable global para colocar una direccion por defecto
        // en caso el cliente lo requiera, sino se muestra la direccion del establecimiento que genera esta venta
        $domicilio_fiscal = "";

        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout("none");
        $doc_cabecera = $this->getConfig('doc_cabecera', '');
        $doc_pie = $this->getConfig('doc_pie_nv', '');
        $this->viewBuilder()->setVars([
            'logo'              => $logo,
            'razon_social'      => $this->datos_empresa['razon_social'],
            'ruc'               => $this->datos_empresa['ruc'],
            'domicilio_fiscal'  => $domicilio_fiscal,
            'venta'             => $venta,
            'pagos'             => $pagos && count($pagos) > 0 ? $pagos : null,
            'usuario'           => $usuario,
            //'qr_filename'       => $this->emisoresCtrl->getBasePathQR() . "{$factura->nombre_unico}.png",
            'doc_cabecera'      => $doc_cabecera,
            'doc_pie_nv'           => $doc_pie,
        ]);
        $html = $this->render("/Ventas/modelo_a4");

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        // para que se reconozca la ruta base
        $dompdf->getOptions()->setChroot(ROOT);


        $options->set('default_font', 'Helvetica');
        //$options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4");
        $dompdf->render();

        $ruta_pdf = $this->getPathPdf("ventas") . "{$venta->documento_serie}-{$venta->id}-a4.pdf";
        // guardamos en disco
        $output = $dompdf->output();
        file_put_contents($ruta_pdf, $output);
        return $ruta_pdf;
    }

    public function descargarNotaVentaPdf($venta_id, $formato = 'a4', $descargar = '1'){
        $venta = $this->Ventas->get($venta_id);

        $nombre_archivo = "{$venta->documento_serie}-{$venta->id}-a4.pdf";

        $ruta_pdf = $this->getPathPdf("ventas") . $nombre_archivo;
        if(file_exists($ruta_pdf)){
            $response = $this->response->withFile($ruta_pdf,[
                'download' => ($descargar == '1'),
                'name' => $nombre_archivo
            ]);
            return $response;
        } else {
            echo "el archivo no existe o ha sido movido";
            exit;
        }
    }

    public function apiImprimirPdf($venta_id, $formato = 'a4'){
        $venta = $this->Ventas->get($venta_id);

        $nombre_archivo = "{$venta->documento_serie}-{$venta->documento_correlativo}-{$formato}.pdf";
        $ruta_pdf =  $this->getPathPdf("ventas") . $nombre_archivo;
        if(file_exists($ruta_pdf)){
            $respuesta =
                [
                    'success'   =>  true,
                    'data'      =>  base64_encode(file_get_contents($ruta_pdf)),
                    'message'   =>  'Archivo encontrado'
                ];
        } else {
            $respuesta =
                [
                    'success'   =>  false,
                    'data'      =>  'el archivo no existe o ha sido movido',
                    'message'   =>  ''
                ];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }



    public function generarNotaVentaPdfTicket($venta_id = '0'){

        $venta = $this->Ventas->get($venta_id, ['contain' => 'VentaRegistros']);

        $logo =  WWW_ROOT. $this->getConfig("global_logo");

        $usuario = $this->Usuarios->find()->where(['id' => $venta->usuario_id])->first();


        // TODO implementar una variable global para colocar una direccion por defecto
        // en caso el cliente lo requiera, sino se muestra la direccion del establecimiento que genera esta venta
        $domicilio_fiscal = "";
        $doc_cabecera = $this->getConfig('doc_cabecera', '');
        $doc_pie = $this->getConfig('doc_pie_nv', '');

        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout("none");
        $this->viewBuilder()->setVars([
            'logo'              => $logo,
            'razon_social'      => $this->datos_empresa['razon_social'],
            'ruc'               => $this->datos_empresa['ruc'],
            'domicilio_fiscal'  => $domicilio_fiscal,
            'venta'             => $venta,
            'usuario'           => $usuario,
            //'qr_filename'       => $this->emisoresCtrl->getBasePathQR() . "{$factura->nombre_unico}.png"
            'doc_cabecera'      => $doc_cabecera,
            'doc_pie_nv'        => $doc_pie,
        ]);
        $html = $this->render("/Ventas/modelo_ticket");

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        // para que se reconozca la ruta base
        $dompdf->getOptions()->setChroot(ROOT);

        $options->set('default_font', 'Helvetica');
        $options->set('dpi', 72);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        //$dompdf->setPaper([0,0,204,650]);
        //$dompdf->setPaper([0,0,204,650]);
        $dompdf->setPaper([0,0,220,800]);
        $dompdf->render();

        $ruta_pdf = $this->getPathPdf("ventas") . "{$venta->documento_serie}-{$venta->id}-ticket.pdf";
        // guardamos en disco
        $output = $dompdf->output();
        file_put_contents($ruta_pdf, $output);
        //return $dompdf->stream($ruta_pdf, array('Attachment' => true));
        return $ruta_pdf;
    }

    public function descargarNotaVentaTicket($venta_id, $formato = 'ticket', $descargar = '1'){
        $venta = $this->Ventas->get($venta_id);

        $nombre_archivo = "{$venta->documento_serie}-{$venta->id}-ticket.pdf";

        $ruta_pdf = $this->getPathPdf("ventas") . $nombre_archivo;
        if(file_exists($ruta_pdf)){
            $response = $this->response->withFile($ruta_pdf,[
                'download' => ($descargar == '1'),
                'name' => $nombre_archivo
            ]);
            return $response;
        } else {
            echo "el archivo no existe o ha sido movido";
            exit;
        }
    }

    public function misVentas($usuario_id) {


        $factura = $this->request->getQuery("factura", "");
        $fini = $this->request->getQuery("fecha_ini");
        $fini = ($fini == '') ? date("Y-m-d", strtotime("-1 week", time())) : $fini;
        $ffin = $this->request->getQuery("fecha_fin");
        $ffin = ($ffin == '') ? date("Y-m-d"): $ffin;

        $exportar = $this->request->getQuery("exportar");
        $tipo_venta = $this->request->getQuery("tipo_venta");

        // nuevos filtros para el reporte del contador
        // $opt_estado = $this->request->getQuery("opt_estado");
        $opt_documento_tipo = $this->request->getQuery("opt_documento_tipo");
        $opt_nombres = $this->request->getQuery("opt_nombres", "") ?? "";

//        echo $usuario_id;
//        exit();

        $usuario = $this->Usuarios->get($usuario_id);

        $ventas = $this->Ventas->find()->where([
            'usuario_id' => $usuario_id,
            'fecha_venta >= ' => $fini . " 00:00:00",
            'fecha_venta <= ' => $ffin . " 23:59:59",
        ]);

        if ($opt_nombres != "") {
            $ventas = $ventas->andWhere(['cliente_razon_social LIKE ' => "%$opt_nombres%",]);
        }

        $ventas2 = $this->Ventas->find()->where([
            'usuario_id' => $usuario_id,
            'fecha_venta >= ' => $fini . " 00:00:00",
            'fecha_venta <= ' => $ffin . " 23:59:59",
        ])->orderAsc('fecha_venta');

        if ($opt_nombres != "") {
            $ventas2 = $ventas2->andWhere(['cliente_razon_social LIKE ' => "%$opt_nombres%",]);
        }

        /*
        if ($opt_estado != ''){
            $ventas = $ventas->andWhere(['estado' => $opt_estado]);
        }*/
//        if ($opt_documento_tipo != ''){
//            $ventas = $ventas->andWhere(['documento_tipo' => $opt_documento_tipo]);
//            $ventas2 = $ventas2->andWhere(['documento_tipo' => $opt_documento_tipo]);
//        }

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
            'title'  =>  "Mis Ventas",
            'links' =>[
                'btnNuevo'  =>  [
                    'name'  =>  "<i class='fas fa-plus fa-fw'></i> Nueva Venta",
                    'params'    =>  ['action' => 'nuevaVenta']
                ]
            ]
        ];
        $ventasTotales = clone $ventas;
        $ventasTotales->select([
            'sum_total' => $ventasTotales->func()->sum('Ventas.total'),
            'sum_pagos' => $ventasTotales->func()->sum('Ventas.total_pagos'),
            'sum_deuda' => $ventasTotales->func()->sum('Ventas.total_deuda'),
        ]);

        if ( $exportar == 1){
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

        $respuesta = [
            'success' => true,
            'data' => $ventas,
            'message' => "Listado exitoso",
        ];

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function apiGetByFilter(){

        $q = $this->request->getQuery('query');

        $ventas = $this->Ventas->find()
            ->where
            (["CONCAT_WS('-',Ventas.documento_serie,Ventas.documento_correlativo) LIKE"=>"%{$q}%"]
            );

        $ventas_array = [];
        foreach ($ventas as $venta){
            $ventas_array[] = [
              'id' => $venta['id'],
              'value'=> $venta['documento_serie'].'-'.$venta['documento_correlativo'].' - '.$venta['cliente_razon_social'],
                'codigo' => $venta['documento_serie'].'-'.$venta['documento_correlativo'],
                'cliente_razon_social' => $venta['cliente_razon_social'],
               'cliente_doc_numero' => $venta['cliente_doc_numero'] ,
                'fecha_venta' => $venta['fecha_venta'] ,
                'total' => $venta['total']
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'query' => $q,
                'suggestions' => $ventas_array
            ]));


    }

    public function apiFetchByCorrelativo($q){

        $serie_correl = explode("-", $q);
        $venta = $this->Ventas->find()->where(['documento_serie' => $serie_correl[0], 'documento_correlativo' => $serie_correl['1']])->contain(['VentaRegistros'])->first();

        $resp = [];
        if ($venta){
            $resp = [
                'success'   =>  true,
                'data'      =>  $venta,
                'message'   =>  'ok'
            ];
        }else{
            $resp = [
                'success'   =>  false,
                'data'      =>  "",
                'message'   =>  "La venta con Numeración {$q} no existe"
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($resp));
    }

    public function getClientePorVenta($venta_id){

        $respuesta =
            [
              'success'=>false,
              'data'=>'',
              'message'=>'No se han encontrado los datos del cliente'
            ];

        $venta = $this->Ventas->find()->where(['id'=>$venta_id])->first();
        if($venta->cliente_doc_tipo == "6"){
            $cliente = $this->Cuentas->find()->where(['ruc'=>$venta->cliente_doc_numero])->first();

        }else {
            $cliente = $this->Personas->find()->where(['dni'=>$venta->cliente_doc_numero])->first();
        }
        if($cliente != null && count($cliente->toArray()) > 0){

            $respuesta =
                [
                  'success'=>true,
                  'data'    =>  $cliente  ,
                  'message' =>  'Busqueda Exitosa'
                ];
        }
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }



    public function cambiarEstadoVenta($venta_id){
        $nuevo_estado = $this->request->getData('nuevo_estado');

        $respuesta =
            [
                'success'   =>  false,
                'data'      =>  '',
                'message'   =>  'No se han encotrado datos'
            ];

        $ventaObj = $this->Ventas->find()->where(['id'=>$venta_id])->first();
        $ventaObj->estado_sunat    =   $nuevo_estado;

        if($this->Ventas->save($ventaObj) ){

            $respuesta =
                [
                    'success' =>  true,
                    'data'    =>  [$ventaObj],
                    'message' =>  'Estado cambiado con exito'
                ];
        }else{
            $respuesta =
                [
                    'success' =>  false,
                    'data'    =>  [$ventaObj],
                    'message' =>  'Ha habido un error con el cambio de estado'
                ];
        }
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }

    public function inicio()
    {
        $fecha_actual = Date('Y-m-d');
        $fecha_actual = Date('Y-m-d', strtotime($fecha_actual . "+ 1 days"));
        $primero_del_mes = Date('Y-m-01');



        /**
         * Extraemos las ventas correspondientes
         */

        $ventas = $this->Ventas->find()
            ->where(['fecha_venta >=' => $primero_del_mes . " 00:00-00:00",
                'fecha_venta <=' => $fecha_actual . " 00:00-00:00","estado !=" => "ANULADO"]);
        $ventas = $ventas
            ->select(
                [
                    'total' => $ventas->func()->sum('Ventas.total'),
                    'fecha_venta' => 'DATE(Ventas.fecha_venta)'
                ]
            )
            ->group(['DATE(Ventas.fecha_venta)']);



        $this->set('view_title',"Histórico de Ventas");
        $this->set('ventas',$ventas);
    }


    /**
     * Esta funcion funciona como API
     * recibe un archivo excel y lo parsea, devuelve los siguientes datos
     * - RUC
     * - Razon Social
     * - Items
     * @author  Gerber Pacheco
     */
    public function ventaDesdeExcelAjax(){
        $respuesta = [
            'success'   =>  false,
            'data'      =>  'error',
            'message'   =>  'enviar archivo excel a través de POST'
        ];

        if($this->request->is(['POST', 'PUT', 'PATCH'])){
            $data = $this->request->getData();

            $inputFileName = $data['excel']->getStream()->getMetadata('uri');

            /**  Identify the type of $inputFileName  **/
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);

            $datos_excel = [];

            /**
             * Aqui comienza la lectura de los datos
             * Primero extraemos estas 3 palabras
             * RUC/DNI, CLIENTE, CANTIDAD
             */
            $row_cantidad = 0;
            for($row = 1; $row < 10; $row++){
                $label = $spreadsheet->getActiveSheet()->getCell("2", $row)->getValue();
                $value = $spreadsheet->getActiveSheet()->getCell("3", $row)->getValue();

                if (strtoupper((string)$label) == 'RUC/DNI') {
                    $datos_excel['cliente_doc_nro'] = $value;
                }
                if (strtoupper((string)$label) == 'CLIENTE') {
                    $datos_excel['cliente_razon_social'] = $value;
                }
                if (strtoupper((string)$label) == 'CANTIDAD') {
                    $row_cantidad = $row;
                }
            }

            /**
             * Aqui comienza la lectura de los items
             * como ya obtuvimos el numero de fila donde se encuentra la palabra "CANTIDAD"
             * comenzamos a leer desde esa fila hacia abajo
             */
            if ($row_cantidad != 0){
                do{
                    $data = [];
                    $data['cantidad'] = $spreadsheet->getActiveSheet()->getCell("2", $row_cantidad + 1 )->getValue();
                    $data['unidad'] = $spreadsheet->getActiveSheet()->getCell("3", $row_cantidad + 1 )->getValue();
                    $data['nombre'] = $spreadsheet->getActiveSheet()->getCell("4", $row_cantidad + 1 )->getValue();
                    $data['valor_venta'] = $spreadsheet->getActiveSheet()->getCell("5", $row_cantidad + 1 )->getValue();
                    $data['total'] = $spreadsheet->getActiveSheet()->getCell("6", $row_cantidad + 1 )->getValue();

                    $row_cantidad ++;

                    if($data['cantidad'] != ''){
                        $datos_excel['items'][] = $data;
                    }

                }while($data['cantidad'] != '');
            }



            $respuesta = [
                'success'   =>  true,
                'data'      =>  $datos_excel,
                'message'   =>  'ok'
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }


    /**
     * @author Dylan
     * funciones para el contador, para visualizar registros
     */
    public function reporteVentas(){
        $periodo = $this->request->getQuery('periodo');
        $periodo = $periodo == null ? date('Y-m') : (substr($periodo, 0, 4) . "-" . substr($periodo, -2));
        $periodo = date('Y-m',strtotime($periodo));

        $f_inicio = date("{$periodo}-01");
        $f_fin = date("Y-m-t", strtotime("{$periodo}-01"));

        $ventas = $this->Ventas
            ->find()
            ->contain(['VentaRegistros'])
            ->where(
                [
                    'fecha_venta >=' => "{$f_inicio} 00:00:00",
                    'fecha_venta <=' => "{$f_fin} 23:59:59" ,
                    'OR' => [ ['documento_tipo' => 'BOLETA'],
                        [ 'documento_tipo' => 'FACTURA' ] ]
                ]);

        $totales = [
            'pen_total' => 0,
            'pen_op_gravadas' => 0,
            'pen_op_gratuitas' => 0,
            'pen_op_exoneradas' => 0,
            'pen_op_inafectadas' => 0,
            'usd_total' => 0,
            'usd_op_gravadas' => 0,
            'usd_op_gratuitas' => 0,
            'usd_op_exoneradas' => 0,
            'usd_op_inafectadas' => 0,
        ];
        $ventasTotales = clone $ventas;
        foreach($ventas as $venta){
            if($venta->tipo_moneda == 'PEN'){
                $totales['pen_total'] +=  $venta->total;
                $totales['pen_op_gravadas'] +=  $venta->op_gravadas;
                $totales['pen_op_gratuitas'] +=  $venta->op_gratuitas;
                $totales['pen_op_exoneradas'] +=  $venta->op_exoneradas;
                $totales['pen_op_inafectadas'] +=  $venta->op_inafectadas;
            }elseif($venta->tipo_moneda == 'USD'){
                $totales['usd_total'] +=  $venta->total;
                $totales['usd_op_gravadas'] +=  $venta->op_gravadas;
                $totales['usd_op_gratuitas'] +=  $venta->op_gratuitas;
                $totales['usd_op_exoneradas'] +=  $venta->op_exoneradas;
                $totales['usd_op_inafectadas'] +=  $venta->op_inafectadas;
            }
        }

        $exportar = $this->request->getQuery("exportar");
        if ( $exportar == 1){
            $repo = new ReporteVentasController(new ServerRequest());
            return $repo->exportarVenta($ventas, [ // $ventas2
                'fecha_inicio'  =>  [
                    'label' =>  'Fec. Inicio',
                    'value' =>  $f_inicio
                ],
                'fecha_fin'     =>  [
                    'label' =>  'Fec. Fin',
                    'value' =>  $f_fin
                ]
            ]);
            exit;
        }

        $exportar_completo = $this->request->getQuery("exportar_completo");
        if ( $exportar_completo == 1){
            $reportesCtrl = new ReportesController(new ServerRequest());
            return $reportesCtrl->reporteComprasVentasContador();
            exit;
        }

        $ventas->limit(10)->orderBy(['fecha_venta' => 'DESC']);
        $ventas = $this->paginate($ventas);

        $top_links = [
            'title'  =>  "Reporte Ventas",
        ];
        $this->set("view_title", "Reporte de Ventas (Vista Contador)");
        $this->set("top_links", $top_links);
        $this->set("ventas", $ventas);

        $periodo = substr($periodo, 0, 4) . substr($periodo, -2);
        $this->set("periodo", $periodo);
        $this->set("totales", $totales);

    }

    public function reporteVentaDetalles($vid){
        $venta = $this->Ventas->get($vid, [
            'contain' => ['VentaRegistros']
        ]);
        $documento_id = "{$venta->documento_serie}-{$venta->documento_correlativo}";

        $top_links = [
            'title'  =>  "Detalles de la Venta $documento_id",
        ];

        $emisoresCtrl = new SunatFeEmisoresController(new ServerRequest());
        $emisor = $emisoresCtrl->getEmisor();

        $all_items = $this->Items->find();
        foreach ($all_items as $it) {
            $it->value = $it->nombre;
        }
        $this->set(compact('all_items'));

        $this->set("venta", $venta);
        $this->set("emisor", $emisor);
        $this->set("top_links", $top_links);
        $this->set("documento_id", $documento_id);
        $this->set("view_title", "Detalles de $documento_id");
    }

    public function exportarJson($id_venta = 0 ){
        $venta = $this->Ventas->find()->where(['Ventas.id' => $id_venta])->contain(['VentaRegistros'])->first();
        if($venta && $venta->sunat_fe_factura){
            $name = "{$venta->sunat_fe_factura->emisor_ruc}-{$venta->sunat_fe_factura->serie}-{$venta->sunat_fe_factura->correlativo}";
            $json = json_encode($venta);
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="'. $name . '.json"');
            header('Content-Length: ' . strlen($json));
            echo $json;
        }
        exit;
    }
    public function importarJson(){
        if ($this->request->is(['post', 'patch', 'put'])) {
            if($this->cargarRegistrosJson()){
                $this->Flash->success('Registros cargados.');
            }else{
                $this->Flash->error("No se pudo cargar el registro, revise si el archivo es correcto.");
            }
        }
        $this->set("view_title", "Configuraciones de Ventas");
        $top_links = [
            'title' => 'Cargar un registro de venta'
        ];
        $this->set(compact('top_links'));
    }
    public function cargarRegistrosJson(){
        try {
            if( $_FILES['archivo']['type'] == 'application/json' ) {
                $this->guardarRegistrosJson( file_get_contents($_FILES['archivo']['tmp_name']) );
                return true;
            }else{
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function guardarRegistrosJson($jsonStr) {
        $obj = json_decode($jsonStr);
        $exist =  $this->Ventas->find()->where(['documento_serie' => $obj->documento_serie , 'documento_correlativo' => $obj->documento_correlativo])->first();
        if(!$exist){
            $ventaSave = $this->Ventas->newEntity([
                'usuario_id' => $this->usuario_sesion['id'] ?? 0,
                // 'factura_id' => $obj->factura_id,
                'cliente_id' => $obj->cliente_id,
                'emisor_ruc' => $obj->emisor_ruc,
                'emisor_razon_social' => $obj->emisor_razon_social,
                'cliente_doc_tipo' => $obj->cliente_doc_tipo,
                'cliente_doc_numero' => $obj->cliente_doc_numero,
                'cliente_razon_social' => $obj->cliente_razon_social,
                'cliente_domicilio_fiscal' => $obj->cliente_domicilio_fiscal,
                'documento_tipo' => $obj->documento_tipo,
                'documento_serie' => $obj->documento_serie,
                'documento_correlativo' => $obj->documento_correlativo,
                'nombre_unico' => $obj->nombre_unico,
                'estado' => $obj->estado,
                'sunat_cdr_rc' => $obj->sunat_cdr_rc,
                'sunat_cdr_msg' => $obj->sunat_cdr_msg,
                'estado_sunat' => $obj->estado_sunat,
                'subtotal' => $obj->subtotal,
                'igv_percent' => $obj->igv_percent,
                'igv_monto' => $obj->igv_monto,
                'isc_monto' => $obj->isc_monto,
                'icb_per_monto' => $obj->icb_per_monto,
                'op_gravadas' => $obj->op_gravadas,
                'op_gratuitas' => $obj->op_gratuitas,
                'op_exoneradas' => $obj->op_exoneradas,
                'op_inafectas' => $obj->op_inafectas,
                'total' => $obj->total,
                'total_en_letras' => $obj->total_en_letras,
                'total_pagos' => $obj->total_pagos,
                'total_deuda' => $obj->total_deuda,
                'monto_credito' => $obj->monto_credito,
                'tipo_operacion' => $obj->tipo_operacion,
                'cod_detraccion' => $obj->cod_detraccion,
                'porcentaje_detraccion' => $obj->porcentaje_detraccion,
                'monto_detraccion' => $obj->monto_detraccion,
                'total_items' => $obj->total_items,
                'fecha_venta' => $obj->fecha_venta,
                'fecha_poranular' => $obj->fecha_poranular,
                'fecha_anulacion' => $obj->fecha_anulacion,
                'fecha_vencimiento' => $obj->fecha_vencimiento,
                'motivo_anulacion' => $obj->motivo_anulacion,
                'tipo_moneda' => $obj->tipo_moneda,
                'comentarios' => $obj->comentarios,
                'guia_remision' => $obj->guia_remision,
                'codvendedor' => $obj->codvendedor,
                'nro_ref' => $obj->nro_ref,
                'forma_pago' => $obj->forma_pago,
            ]);
            $ventaSave = $this->Ventas->save($ventaSave);
            if($ventaSave){
                foreach($obj->venta_registros as $reg){
                    $registroSave = $this->VentaRegistros->newEntity([
                        'planilla_id' => $reg->planilla_id,
                        'planilla_registro_id' => $reg->planilla_registro_id,
                        'venta_id' => $ventaSave->id,
                        'item_index' => $reg->item_index,
                        'item_id' => $reg->item_id,
                        'item_codigo' => $reg->item_codigo,
                        'item_nombre' => $reg->item_nombre,
                        'item_comentario' => $reg->item_comentario,
                        'item_unidad' => $reg->item_unidad,
                        'cantidad' => $reg->cantidad,
                        'precio_uventa' => $reg->precio_uventa,
                        'valor_venta' => $reg->valor_venta,
                        'subtotal' => $reg->subtotal,
                        'igv_monto' => $reg->igv_monto,
                        'isc_monto' => $reg->isc_monto,
                        'icb_per_monto' => $reg->icb_per_monto,
                        'precio_total' => $reg->precio_total,
                        'afectacion_igv' => $reg->afectacion_igv,
                    ]);
                    $registroSave = $this->VentaRegistros->save($registroSave);
                }
            }
        }
    }
    public function apiVentaForNota(){
        $q = $this->request->getData('documento', '');
        $serie_correl = explode("-", $q);
        $venta = $this->Ventas->find()
        ->where([
            'documento_serie' => ($serie_correl[0] ?? ''), 'documento_correlativo' => ($serie_correl['1'] ?? ''),
        ])
        ->andWhere(
            function ($exp) {
                return $exp->notIn('Ventas.estado',  [ 'ANULADO', 'MODIFICADO', 'ANULACION_NC', 'ANULACION_ND' ]);
            }
        )
        ->contain(['VentaRegistros'])->first();
        $resp = [];
        if ($venta){
            $resp = [
                'success'   =>  true,
                'data'      =>  $venta,
                'message'   =>  'ok'
            ];
        }else{
            $resp = [
                'success'   =>  false,
                'data'      =>  "",
                'message'   =>  "La venta con Numeración {$q} no existe o ya fué modificada/anulada."
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($resp));
    }

}
