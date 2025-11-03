<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\AppController;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;
use Cake\Http\ServerRequest;

/**
 * Cotizaciones Controller
 *
 * @property \App\Model\Table\CotizacionesTable $Cotizaciones
 * @method \App\Model\Entity\Cotizacione[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CotizacionesController extends AppController
{

    var $tiposdoc = [
        "BOLETA"    =>  'Boleta de Venta',      // 03
        "FACTURA"    =>  'Factura',              // 01
        "NOTAVENTA"    =>  'Nota de Venta'         // 00
    ];

    var $tiposventa = [
        '1'   =>  'Contado',
        '2'   =>  'Crédito'
    ];
    private $emisoresCtrl = null;
    private $configCtrl = null;

    protected $Cuentas = null;
    protected $Items = null;
    protected $ItemCategorias = null;
    protected $ItemMarcas = null;
    protected $CotizacionRegistros = null;
    protected $Usuarios = null;
    public function initialize(): void {
        parent::initialize();
        $this->Cuentas = $this->fetchTable('Cuentas');
        $this->Items = $this->fetchTable('Items');
        $this->ItemCategorias = $this->fetchTable('ItemCategorias');
        $this->ItemMarcas = $this->fetchTable('ItemMarcas');
        $this->CotizacionRegistros = $this->fetchTable("CotizacionRegistros");
        $this->Usuarios = $this->fetchTable("Usuarios");
        $whatsapp_active = $this->getConfig('whatsapp_active', "0");
        $this->set(compact('whatsapp_active'));
    }

    public function beforeRender(\Cake\Event\EventInterface $event) {
        parent::beforeRender($event);
    }

    public function regenerarPdfs($id){
        $this->generarPdfA4($id);

        echo "Proforma regenerada exitosamente";
        exit();
    }

    public function index(){
        $exportar = $this->request->getQuery("exportar");
        $reporte = $this->request->getQuery("reporte");
        $fini = $this->request->getQuery("fecha_ini");
        $fini = ($fini == '') ? date("Y-m-d", strtotime("-1 week", time())) : $fini;
        $ffin = $this->request->getQuery("fecha_fin");
        $ffin = ($ffin == '') ? date("Y-m-d"): $ffin;
        $opt_nombres = $this->request->getQuery("opt_nombres", "");

        $this->set(compact('opt_nombres'));

        $cotizaciones = $this->Cotizaciones->find()->where([
            'Cotizaciones.fecha_cotizacion >= ' => $fini . " 00:00:00",
            'Cotizaciones.fecha_cotizacion <= ' => $ffin . " 23:59:59",
            'Cotizaciones.cliente_razon_social LIKE ' => "%$opt_nombres%",
        ])->order(['Cotizaciones.fecha_cotizacion' => 'DESC'])->contain([ 'Usuarios']);

        $this->paginate = [
            'limit' => 10,
        ];

        $query_tventas = $this->Cotizaciones->find()->where([
        ]);
        $query_total_ventas = $query_tventas->select(['count' => $query_tventas->func()->sum('total')])->first();

        $top_links = [];
        $top_links['links'] = [];
        $top_links['title'] ="Mis Proformas";

        $top_links['links']['btnNuevo'] = [
            'name'  =>  "<i class='fas fa-plus fa-fw'></i> Nueva Proforma",
            'params'    =>  ['controller' => 'Cotizaciones', 'action' => 'nueva']
        ];

        // si se ha solicitado exportar, enviamos el resultado del query a la funcion de exportar
        if ($reporte == 1){
            return $this->exportar($cotizaciones, [
                'Fecha Inicio'  =>  $fini,
                'Fecha Fin'     =>  $ffin
            ]);
            exit;
        }

        $cotizaciones = $this->paginate($cotizaciones);

        $this->set("cotizaciones", $cotizaciones);
        $this->set("fini", $fini);
        $this->set("ffin", $ffin);
        $this->set("top_links", $top_links);
        $this->set("view_title", "Proformas");
        $this->set("titulo", "Reporte e Historial de Proformas");
    }

    public function delete($cotizacion_id = "") {
        if ($this->request->is(['POST', 'DELETE'])) {
            try {
                $item = $this->Cotizaciones->get($cotizacion_id);
                $this->Cotizaciones->delete($item);
                $this->Flash->success('Eliminacion exitosa');
            } catch (\Throwable $e) {
                $this->Flash->error('Error al eliminar');
            }
            return $this->redirect(['action' => 'index']);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function detalles($vid){
        $coti = $this->Cotizaciones->get($vid, [
            'contain' => ['CotizacionRegistros', 'Cuentas']
        ]);

        $this->set("coti", $coti);
        $this->set("view_title", "Detalle de la Proforma");
        $this->set("title", "Detalles de la " . $coti->documento_tipo . ": " . $coti->documento_nro);
    }

    public function eliminadas(){
        $this->paginate = ['limit' => 10, 'order' => ['orden' => 'ASC'], 'contain' => ['Cuentas']];
        $ventas = $this->paginate($this->Ventas->find()->where(['estado' => 'E']));

        $top_links = [
            'txtTitulo'  =>  [
                'tipo'      =>  'text',
                'nombre'    =>  "Ventas Eliminadas",
            ]
        ];

        $this->set("view_title", "Ventas");
        $this->set("top_links", $top_links);

        $this->set("ventas", $ventas);
        $this->set('_serialize', ['ventas']);
        $this->set("title", "Historial de Ventas");
    }


    public function eliminar($id = null)
    {
        /*
         * Al eliminar una venta
         * - se cambia el estado a E para su revision, solo el administrador puede eliminarla permanentemente
         * - se restaura el stock de los registros
         */
        $this->request->allowMethod(['post', 'delete']);
        $coti = $this->Cotizaciones->get($id);
        if ($this->Cotizaciones->delete($coti)) {

            $this->CotizacionRegistros->deleteAll(['cotizacion_id' => $id]);
            $this->Flash->success("La Proforma se ha eliminado");
        } else {
            $this->Flash->error(__('Ha ocurrido un error, por favor, intente nuevamente.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function nueva(){
        $coti_id = $this->request->getQuery('coti_id', '');

        $servicios = $this->Items->find()->select(['id', 'codigo', 'nombre', 'precio_venta'])->where(["unidad"=>"ZZ"]);
        $this->set(compact('servicios'));

        if($this->request->is('post')){
            $data = $this->request->getData();
            $data['fecha_cotizacion'] = $data['fecha_venta'] . " " . $data['hora_venta'];
            $venc = strtotime( "{$data['fecha_cotizacion']} +". $data['dias_vencimiento'] . " days");
            $data['fecha_poranular'] =  date( "Y-m-d 23:59:59" ,$venc);

            try {
                $cotizacion = $this->registrarCotizacion($data);
                if ($cotizacion) {

                    $respuesta =
                        [
                            'success'  => true,
                            'data'    =>  $cotizacion,
                            'imprimir'  =>    isset($data['imprimir']) ? $data['imprimir'] : "0",
                            'message' =>  'Proforma guardada exitosamente'
                        ];

                } else {

                    $respuesta =
                        [
                            'success'  => false,
                            'data'    =>  $cotizacion,
                            'message' =>  'Ha ocurrido un error, por favor, intente nuevamente'
                        ];

                }

            } catch (\Throwable $e) {

                $respuesta =
                    [
                        'success'  => false,
                        'data'    =>  '',
                        'message' =>  'Ha ocurrido un error al momento de emitir la cotizacion: ' . $e->getMessage()
                    ];

                $this->Flash->error('Error al registrar proforma: '.$e->getMessage());
                $this->Flash->error('Error al registrar proforma');
            }

            return $this->response
                        ->withType('application/json')
                        ->withStringBody(json_encode($respuesta));


        }

        $this->set('usuario_sesion', $this->usuario_sesion);

        $usuarios = $this->Usuarios->find('list');
        $this->set(compact('usuarios'));

        // links que se muestra en la vista
        $top_links = [
            'title'  =>  "Nueva Proforma",
            'links' => [
                'btnBack'   =>  [
                    'name' => "<i class='fa fa-database fa-fw'></i> Proformas",
                    'params' => [ 'controller'=>'Cotizaciones', 'action' => 'index' ]
                ]
            ]
        ];

        $this->set(compact('coti_id'));
        $this->set("tipodoc_defecto", $this->getConfig("ven_docdefault"));
        $this->set("tiposdoc", $this->tiposdoc);
        $this->set("view_title", "Proformas");
        $this->set("top_links", $top_links);

    }


    public function procesarCoti($coti_id){

        $this->set(compact("coti_id"));
        $this->set('view_title',"Procesar Coti");

    }

    public function registrarCotizacionDesdeVenta($data){
        $data['fecha_cotizacion']   = $data['fecha_venta'];

        //print_r($data);exit;

        return $this->registrarCotizacion($data);
    }

    private function registrarCotizacion($data){

        $cotizacion = $this->Cotizaciones->find()->where(['id'=>$data['cotizacion_id']])->first();

        if(!$cotizacion)
        {
            $cotizacion = $this->Cotizaciones->newEmptyEntity();
        }else{
            $registros = $this->CotizacionRegistros->find()->where(['cotizacion_id' => $cotizacion->id]);
            $ids= [];
            foreach ($registros as $registro)
            {
                $ids[] = $registro->id;
            }
            if(count($ids) > 0)
            {
                $this->CotizacionRegistros->deleteAll(['id IN'=>$ids]);
            }
        }

        $data['fecha_venta'] = $data['fecha_venta'] . " " . $data['hora_venta'];


        $cotizacion = $this->Cotizaciones->patchEntity($cotizacion, $data);


        if ($cotizacion->documento_tipo == 'BOLETA' && $cotizacion->cliente_razon_social == ''){
            $cotizacion->cliente_razon_social = 'CLIENTE VARIOS';
            $cotizacion->cliente_doc_numero = '99999999';
            $cotizacion->cliente_doc_tipo = '1';
        }

        // $cotizacion->total_pagos = $data['pago_inicial'];
        //echo "<pre>";    print_r($data);    echo "</pre>";      exit;

        $cotizacion->total_deuda    = ($cotizacion->total - $cotizacion->total_pagos);

        $cotizacion->dcto_percent /= 100.0;
        $cotizacion->total = $cotizacion->nuevo_total;

        //Calculado subtotales
        $cotizacion->op_gravadas     = floatval($data['mto_oper_gravadas']);
        $cotizacion->op_gratuitas    = 0;
        $cotizacion->op_exoneradas   = floatval($data['mto_oper_exoneradas']);
        $cotizacion->op_inafectas    = floatval($data['mto_oper_inafectas']);


        // calculando el IGV
        $cotizacion->igv_percent     = 0.18;
        $cotizacion->subtotal        =  floatval($cotizacion->op_gravadas +
                                        $cotizacion->op_gratuitas +
                                        $cotizacion->op_exoneradas +
                                        $cotizacion->op_inafectas);
        $cotizacion->igv_monto       = floatval($data['mto_oper_gravadas']) * 0.18;

        // $cotizacion->subtotal        = floatval($cotizacion->total) / 1.18;
        // $cotizacion->op_exoneradas   = 0;
        // $cotizacion->op_inafectas    = 0;

        $cotizacion->items_originales = $data['reg_items_originales'];

        $resultado = $this->Cotizaciones->Save($cotizacion);

        if($resultado){
//            $this->registrarPagoInicial($cotizacion->id, $data['pago_inicial'], $data['fecha_venta']);

            // crea los registros de venta y a su vez descuenta el stock
            $cotizacion->total_items = $this->crearCotizacionRegistros($cotizacion, $data['registros']);

            // guardamos la coti
            $cotizacion = $this->Cotizaciones->Save($cotizacion);

            $this->generarPdfTicket($cotizacion->id);
            $this->generarPdfA4($cotizacion->id);

        }
        return $cotizacion;
    }

    /**
     * Crea un registro por cada item de la cotizacion
     * @param type $venta
     * @param type $items
     */

    private function    crearCotizacionRegistros($cotizacion, $items){
        $items_array = json_decode($items, true);

        $index = 0;
        foreach($items_array as $item){
            if($item['item_nombre'] != ''){
                $index++;
                $itemObj = $this->Items->find()->where(['codigo' => $item['item_codigo'] ])->first();
                $reg = $this->CotizacionRegistros->newEmptyEntity();
                $reg->cotizacion_id = $cotizacion->id;
                $reg->item_index = $index;
                $reg->item_id = ($item['item_id'] != '') ? $item['item_id'] : 0;
                $reg->cantidad = ($item['cantidad'] != '') ? $item['cantidad'] : 0;
                $reg->item_nombre = $item['item_nombre'];
                $reg->item_codigo = $item['item_codigo'];
                $reg->item_unidad = $item['item_unidad'];
                $reg->precio_original = 0;
                $reg->item_comentario = isset($item['item_comentario']) ? $item['item_comentario']:"";
                $reg->precio_uventa = floatval($item['precio_uventa']) * (1 - floatval($cotizacion->dcto_percent));
                $reg->tipo_moneda = isset($item['tipo_moneda']) ? $item['tipo_moneda']:"PEN";
//                $reg->precio_uventa_sinigv = $reg->precio_uventa / (1 + $cotizacion->igv_percent);
                $reg->valor_venta = $item['valor_venta'];
                $reg->subtotal = $item['subtotal'];
                //$reg->subtotal = $reg->subtotal * (1 - floatval($cotizacion->dcto_percent));
//                $reg->igv_monto = $reg->subtotal * $cotizacion->igv_percent;
                $reg->precio_total = doubleval($item['precio_total']);//* (1 - floatval($cotizacion->dcto_percent)
                $this->CotizacionRegistros->save($reg);

            }
        }
        return  $index;
    }


    /**
     * Si el cliente no existe entonces se crea uno nuevo
     * @param type $data
     * @return int
     */
    private function getCliente($data){
        if(!isset($data['cliente_id'])) {$data['cliente_id'] = "";}
        if($data['cliente_id'] == "0"){
            // retornamos el ID del cliente generico
            return $this->getConfig("ven_clidefbol_id");
        }

        $clienteController = new CuentasController(new ServerRequest());
        $data_c = [];
        $data_c['id']                   = $data['cliente_id'];
        $data_c['nombre_comercial']     = $data['cliente_razon_social'];
        $data_c['razon_social']         = $data['cliente_razon_social'];
        $data_c['doc_nro']              = $data['cliente_ruc'];
        $data_c['domicilio_fiscal']     = $data['cliente_domicilio_fiscal'];
        $cliente = $clienteController->saveFromArray($data_c);
        return $cliente->id;
    }

    /**
     * Obtiene una cotizacion desde el ID generado
     */
    public function ajaxGetCotiById(){
        $cotiid = $this->request->getQuery("id");

        // si lo ingresado no es numerico
        if(!is_numeric($cotiid)){
            $respuesta = json_encode([
                'estado'    =>  'e',
                'data'      => "No existe la proforma"
            ]);
            return $this->response->withType('application/json')->withStringBody($respuesta);
        }

        // si es numerico, continuamos
        $coti = $this->Cotizaciones->find()->where(['Cotizaciones.id' => $cotiid])->contain(['CotizacionRegistros','Cuentas'])->first();
        if($coti){
            $respuesta = json_encode([
                'estado'    =>  'ok',
                'data'      => $coti
            ]);
        }else{
            $respuesta = json_encode([
                'estado'    =>  'e',
                'data'      => "No existe la proforma"
            ]);
        }

        return $this->response->withType('application/json')->withStringBody($respuesta);
    }



    public function exportar($cotizaciones, $params = []){
        //$data = $this->request->getData();
        $cotizaciones= $cotizaciones->contain(['Usuarios','Cuentas']);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Proformas");
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);
        $index = 2;
        // imprimimos los parámetros del reporte
        foreach ($params as $k => $v){
            $activeSheet->setCellValue("A{$index}", $k);
            $activeSheet->setCellValue("B{$index}", $v);
            $index ++;  // incrmentamos 1 fila
        }
        $index ++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:J{$index}")->getFont()->setBold( true );
        $activeSheet->getColumnDimension('A')->setWidth(20);
        $activeSheet->getColumnDimension('B')->setWidth(25);
        $activeSheet->getColumnDimension('C')->setWidth(25);
        $activeSheet->getColumnDimension('D')->setWidth(30);
        $activeSheet->setCellValue("A{$index}", "Codigo");
        $activeSheet->setCellValue("B{$index}", "Fecha");
        $activeSheet->setCellValue("C{$index}", "Cliente");
        $activeSheet->setCellValue("D{$index}", "Total");
        $total = 0;
        $total_pagos = 0;
        $total_deuda = 0;
        $index ++ ;// agreamos 1 fila más
        foreach ($cotizaciones as $v){
            $c=$this->Cuentas->get($v->cliente_id);

            $activeSheet->setCellValue("A{$index}", $v->id);
            $activeSheet->setCellValue("B{$index}", $v->fecha_cotizacion);
            $activeSheet->setCellValue("C{$index}", $c->razon_social);
            $activeSheet->setCellValue("D{$index}", $v->total);
            // $total += $v->total;
            //$total_pagos += $v->total_pagos;
            // $total_deuda += $v->total_deuda;
            $index ++;
        }
        $index++;   // agrega una fila
        // resumen del reporte, montos totales
        /*$activeSheet->setCellValue("H{$index}", $total_pagos);
        $activeSheet->setCellValue("I{$index}", $total_deuda);
        $activeSheet->setCellValue("J{$index}", $total);*/
        $filename = WWW_ROOT . "reporte_ventas_{$this->usuario_sesion['id']}.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);
        $this->response->withFile($filename,['download' => true,  'name' => "reporte_de_cotizaciones_" . date("Y_m_d") . ".xlsx"]);
        return $this->response;
    }



    public function test() {

    }

    public function generarPdfTicket($cotizacion_id){
        $cotizacion = $this->Cotizaciones->get($cotizacion_id);
//        $venta = $this->Ventas->get($nota->venta_id, ['contain' => 'VentaRegistros']);

        // TODO poner ruta para obtener el logo desde la variable configuraciones
        $logo =  $this->getConfig('global_logo');
        $doc_cab_coti =  $this->getConfig('doc_cab_coti');
        $doc_pie_coti =  $this->getConfig('doc_pie_coti');

        $usuario = $this->Usuarios->find()->where(['id' => $cotizacion->usuario_id])->first();


        // TODO implementar una variable global para colocar una direccion por defecto
        // en caso el cliente lo requiera, sino se muestra la direccion del establecimiento que genera esta venta
        $domicilio_fiscal = "";

        $items = $this->CotizacionRegistros->find()->where(['cotizacion_id' => $cotizacion_id]);

        $this->viewBuilder()->setLayout("none");
        $this->viewBuilder()->setVars(
            [
            'logo'              => $logo,
            'razon_social'      => $this->datos_empresa['razon_social'],
            'ruc'               => $this->datos_empresa['ruc'],
            'domicilio_fiscal'  => $domicilio_fiscal,
            'cotizacion'        => $cotizacion,
            'usuario'           => $usuario,
            'items'             => json_encode($items),
            'doc_cab_coti'      => $doc_cab_coti,
            'doc_pie_coti'      => $doc_pie_coti,
            ]
        );
        $html = $this->render("/Cotizaciones/modelo_ticket");

        $dompdf = new Dompdf();

        $options = $dompdf->getOptions();

        // para que se reconozca la ruta base
        $dompdf->getOptions()->setChroot(ROOT);

        $options->set('default_font', 'Helvetica');
        $options->set('dpi', 72);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper([0,0,220,800]);
        $dompdf->render();

        // esta ruta es absoluta
        $coti_id = str_pad((string)$cotizacion_id, 7, "0", STR_PAD_LEFT);
        $nombre_archivo = "Proforma_{$coti_id}-ticket.pdf";
        $ruta_pdf = $this->getPathPdf("cotizaciones") . $nombre_archivo;

        // guardamos en disco
        $output = $dompdf->output();
        $this->Cotizaciones->save($cotizacion);

        file_put_contents($ruta_pdf, $output);

        return true;

    }

    public function generarPdfA4($cotizacion_id){
        $cotizacion = $this->Cotizaciones->get($cotizacion_id);
//        $venta = $this->Ventas->get($nota->venta_id, ['contain' => 'VentaRegistros']);

        // TODO poner ruta para obtener el logo desde la variable configuraciones
        $logo =  $this->getConfig('global_logo');
        $doc_cab_coti =  $this->getConfig('doc_cab_coti');
        $doc_pie_coti =  $this->getConfig('doc_pie_coti');
        $allow_foto_coti = $this->getConfig('allow_fotos_coti',1);

        $usuario = $this->Usuarios->find()->where(['id' => $cotizacion->usuario_id])->first();

        // TODO implementar una variable global para colocar una direccion por defecto
        // en caso el cliente lo requiera, sino se muestra la direccion del establecimiento que genera esta venta
        $domicilio_fiscal = "";

        $items = $this->CotizacionRegistros
            ->find()
            ->contain(['Items'])
            ->where(['cotizacion_id' => $cotizacion_id]);

        /********* MEMBRETE */
        $membrete = $this->getConfig("membrete_cotizaciones_a4","");
        $membrete_ruta = "";
        if($membrete != ""){
            if(file_exists(WWW_ROOT . $membrete)){
                    $membrete_ruta = WWW_ROOT . $membrete;
            }
        }
        /******************* */


        $this->viewBuilder()->setClassName(null);
        $this->viewBuilder()->setLayout("none");
        $this->viewBuilder()->setVars([
            'logo'              => $logo,
            'razon_social'      => $this->datos_empresa['razon_social'],
            'ruc'               => $this->datos_empresa['ruc'],
            'cotizacion'        => $cotizacion,
            'base'              => WWW_ROOT,
            'usuario'           => $usuario,
            'items'             => json_encode($items),
            'doc_cab_coti'      => $doc_cab_coti,
            'doc_pie_coti'      => $doc_pie_coti,
            'allow_foto_coti'   =>  $allow_foto_coti,
            'membrete_ruta'     =>  $membrete_ruta
        ]);
        $html = $this->render("/Cotizaciones/modelo_a4");

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

        // esta ruta es absoluta
        $coti_id = str_pad((string)$cotizacion_id, 7, "0", STR_PAD_LEFT);
        $nombre_archivo = "Proforma_{$coti_id}-a4.pdf";
        $ruta_pdf = $this->getPathPdf("cotizaciones") .  $nombre_archivo;
        $this->Cotizaciones->save($cotizacion);

        // guardamos en disco
        $output = $dompdf->output();
        file_put_contents($ruta_pdf, $output);

        return true;

    }

    public function generarCoti($coti_id){
        $this->generarPdfA4($coti_id);
        $this->generarPdfTicket($coti_id);
        exit;
    }


    public function apiImprimirPdf($cotizacion_id, $formato = 'a4'){
        $cotizacion_id = str_pad((string)$cotizacion_id, 7, '0', STR_PAD_LEFT);
        $nombre_archivo = "Proforma_{$cotizacion_id}-{$formato}.pdf";

        $cotizacion = $this->Cotizaciones->get($cotizacion_id);

        $ruta_pdf = $this->getPathPdf('cotizaciones') . $nombre_archivo;


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

    public function descargarPdf($cotizacion_id, $formato = 'a4',$descargar = '1'){
        $cotizacion_id = str_pad((string)$cotizacion_id, 7, '0', STR_PAD_LEFT);
        $nombre_archivo = $this->getPathPdf('cotizaciones') . "Proforma_{$cotizacion_id}-{$formato}.pdf";

        $cotizacion = $this->Cotizaciones->get($cotizacion_id);

        $response = $this->response->withFile($nombre_archivo,['download' => $descargar]);
        return $response;

    }

    public function getOne($id = "0") {
        try {
            $coti = $this->Cotizaciones->find()->where([
                'Cotizaciones.id' => $id,
                //'Cotizaciones.fecha_poranular >=' => date("Y-m-d H:i:s")
            ])->contain(['CotizacionRegistros', 'Cuentas'])->first();

            if ($coti) {
                $respuesta = [
                    'success' => true,
                    'data' => $coti,
                    'message' => 'Busqueda exitosa'
                ];
            } else {
                $respuesta = [
                    'success' => false,
                    'data' => '',
                    'message' => "Proforma no encontrada o vencida",
                ];
            }


        } catch (\Throwable $e) {
            $respuesta = [
                'success' => false,
                'data' => '',
                'message' => "ERROR: " . $e->getMessage(),
            ];
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }

    public function generarTodosPdfs() {
        $cotis = $this->Cotizaciones->find();

        foreach ($cotis as $coti) {
            $this->generarPdfA4($coti->id);
            $this->generarPdfTicket($coti->id);
        }

        echo "Generado " . $cotis->count() . " pdfs de cotis";
        exit();
    }

}

