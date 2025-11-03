<?php
namespace App\Controller;

use App\Controller\AppController;
use Dompdf\Dompdf;

use Cake\Http\Client as httpClient;

use Cake\Core\Configure;

/**
 * Stock Controller
 *
 * @property \App\Model\Table\StockTable $Stock
 */
class TareasProgramadasController extends AppController
{
    private $emailCtrl = null;
    private $whastapp_ctrl = null;
    private $emisor_ctrl = null;
    private $ruta_documentos_varios = '';
    public function initialize(): void {
        parent::initialize();
        $this->loadModel('SunatFeResumenDiarios');
        $this->loadModel('Ventas');
        $this->Authentication->allowUnauthenticated(['resumen','enviarResumen','reporteDiarioVentas']);
        $this->emailCtrl = new EmailController();
        $this->whastapp_ctrl = new WhatsappController();
        $this->emisor_ctrl = new SunatFeEmisoresController();
        $this->ruta_documentos_varios ="{$this->emisor_ctrl->getBasePathMedia()}documentos_varios/";
    }

    /**
     * @param string $db---> @author Ali Reyes
     * @author Dylan Ale
     * FUNCION-TPROGRAMADA
     */
    public function resumen($db=''){

        if($db != ''){
            $this->changeDefaultDatabase($db);
        }

        $resumenCtrller = new SunatFeResumenDiariosController();
        $hoy = date('Y-m-d', strtotime('-1 day'));
        $resp = $resumenCtrller->generarResumen($hoy);
        $respuesta = json_decode($resp);
        $resumenes = $this->SunatFeResumenDiarios->find()->where([
            'SunatFeResumenDiarios.estado <>' => 'ACEPTADO',
            'fecha_generacion >=' => $hoy
        ]);
        $resumenCtrl = new SunatFeResumenDiariosController();

        foreach ($resumenes as $res) {

            $res_resumen= $resumenCtrl->consultarTicket($res->id);
            $respuesta_resumen = json_decode($res_resumen);
            if(!$respuesta_resumen->success || $res->estado == 'ERROR' || $res->estado == ''){
                $this->enviarResumen($respuesta_resumen, $res->id);
            }else{
                if($this->getConfig('fe_servidor') == 'PRODUCCION')
                {
                    $this->validarComprobantesEnviados($res->id);
                }
            }
        }


        exit;
    }

    public function validarComprobantesEnviados($resumen_id)
    {
        $this->loadModel("SunatFeFacturas");

        $boletas = $this->SunatFeFacturas->find()->where(["resumen_id" => $resumen_id]);

        $ayer = date('Y-m-d', strtotime('-1 day'));
        $facturas = $this->SunatFeFacturas->find()->where([
            'fecha_emision >=' => $ayer
        ]);

        $array_comprobantes_ids = [];
        foreach ($boletas as $boleta)
        {
            $array_comprobantes_ids[] = $boleta->id;
        }


        $sunatFeFacturas = new SunatFeFacturasController();
        foreach ($array_comprobantes_ids as $id)
        {
            sleep(1);
            $sunatFeFacturas->consultaCpe($id);
        }
        exit;
    }

    /**
     * @author Dylan Ale
     */
    public function enviarResumen($respuesta, $resumen_id){
        ini_set('memory_limit', '-1');
        $resumen = $this->SunatFeResumenDiarios->find()
        ->where(['SunatFeResumenDiarios.id' => (int)$resumen_id ])
        ->contain(['SunatFeFacturas' => ['Ventas']])
        ->first();
        if($resumen){
            $this->viewBuilder()->setLayout("none");
            $this->viewBuilder()->setVars([
                'respuesta'     => $respuesta,
                'resumen'     => $resumen,
            ]);

            $html = $this->render("enviarResumenPdf");
            $dompdf = new Dompdf();
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled',true);
            $dompdf->setOptions($options);

            $dompdf->loadHtml($html);
            $dompdf->setPaper("A4");
            $dompdf->render();
            // $dompdf->stream("pdf_resumen.pdf", ['Attachment' => false ]);
            $output = $dompdf->output();

            file_put_contents( $this->ruta_documentos_varios . "pdf_resumen.pdf", $output);

            $this->emailCtrl->enviarResumenError("pdf_resumen.pdf");
        }
        exit;
    }
    /**
     * @author Dylan Ale
     * FUNCION-TPROGRAMADA
     */
    public function reporteDiarioVentas(){
        $fecha = date('Y-m-d');
        $ventas = $this->Ventas->find()->where(['fecha_venta >=' => "{$fecha} 00:00:00" ])->contain(['Almacenes', 'Usuarios']);

        $this->reporteDiarioVentasPdf($ventas);
        $this->reporteDiarioVentasExcel($ventas);

        // la funcion recibe un rang, como sera de 1 solo dia envio la misma fecha 2 veces
        $zip = $this->zipXml($fecha,  $fecha);
        $zip = (!$zip) ? '': $zip;

        //3 archivos se enviaran por correo, zip el pdf y el excel
        $emailCtrl2 = new EmailController();
        $emailCtrl2->enviarResumenDiario( $zip, "pdf_resumen_diario.pdf", 'excel_resumen_diario.xlsx');
        // $this->reporteDiarioVentasWhatsapp('excel_resumen_diario.xlsx');
        // $this->reporteDiarioVentasWhatsapp('pdf_resumen_diario.pdf');

        //por whatsapp solo se enviara una notificacion de que se envio el correo
        $this->notificacionWhatsapp();
        exit;
    }
    /**
     * @author Dylan Ale
     * genera un excel
     */
    public function reporteDiarioVentasExcel($ventas, $nombre = 'excel_resumen_diario'){
        // $inicio = date('Y-m-d H:i:s', strtotime("+1 day", strtotime(date("Y-m-d H:i:s"))));
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Ventas");
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


        $activeSheet->setCellValue("A{$index}", 'Resumen');
        $index++;  // incrmentamos 1 fila
        $activeSheet->setCellValue("A{$index}", date("d-m-Y"));
        $index++;  // incrmentamos en 1 fila
        $index++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:W{$index}")->getFont()->setBold(true);

        $activeSheet->getColumnDimension('A')->setWidth(10);
        $activeSheet->getColumnDimension('B')->setWidth(15);
        $activeSheet->getColumnDimension('C')->setWidth(15);
        $activeSheet->getColumnDimension('D')->setWidth(10);
        $activeSheet->getColumnDimension('E')->setWidth(7);
        $activeSheet->getColumnDimension('F')->setWidth(10);
        $activeSheet->getColumnDimension('G')->setWidth(10);
        $activeSheet->getColumnDimension('H')->setWidth(10);
        $activeSheet->getColumnDimension('I')->setWidth(7);
        $activeSheet->getColumnDimension('J')->setWidth(15);
        $activeSheet->getColumnDimension('K')->setWidth(85);
        $activeSheet->getColumnDimension('L')->setWidth(35);
        $activeSheet->getColumnDimension('M')->setWidth(35);
        $activeSheet->getColumnDimension('N')->setWidth(35);
        $activeSheet->getColumnDimension('O')->setWidth(35);
        $activeSheet->getColumnDimension('P')->setWidth(35);
        $activeSheet->getColumnDimension('Q')->setWidth(35);
        $activeSheet->getColumnDimension('R')->setWidth(35);
        $activeSheet->getColumnDimension('S')->setWidth(35);
        $activeSheet->getColumnDimension('T')->setWidth(35);
        $activeSheet->getColumnDimension('U')->setWidth(35);
        $activeSheet->getColumnDimension('V')->setWidth(35);
        $activeSheet->getColumnDimension('W')->setWidth(35);
        $activeSheet->getColumnDimension('X')->setWidth(40);
        $activeSheet->getStyle('A6:X6')->applyFromArray($styleArrayTitulo);


        $activeSheet->setCellValue("A{$index}", "Nro");
        $activeSheet->setCellValue("B{$index}", "Fecha");
        $activeSheet->setCellValue("C{$index}", "Fecha");
        $activeSheet->setCellValue("D{$index}", "Tipo de comprobante");
        $activeSheet->setCellValue("E{$index}", "Tipo de comprobante");

        $activeSheet->setCellValue("F{$index}", "Serie");
        $activeSheet->setCellValue("G{$index}", "Nro de comprobante");
        $activeSheet->setCellValue("H{$index}", "Documento");
        $activeSheet->setCellValue("I{$index}", "Documento");

        $activeSheet->setCellValue("J{$index}", "Documento de identidad");
        $activeSheet->setCellValue("K{$index}", "Razon Social");
        $activeSheet->setCellValue("L{$index}", "Base importe de la operación gravada");
        $activeSheet->setCellValue("M{$index}", "Descuento de la base imponible");
        $activeSheet->setCellValue("N{$index}", "Impuesto general de las ventas");
        $activeSheet->setCellValue("O{$index}", "Importe total de la operación exonerada");
        $activeSheet->setCellValue("P{$index}", "Importe total de la operación inafecta");
        $activeSheet->setCellValue("Q{$index}", "Impuesto selectivo al consumo");
        $activeSheet->setCellValue("R{$index}", "IGV del ISC");
        $activeSheet->setCellValue("S{$index}", "Base Imponible de la Operación Gravada de Ventas del Arroz Pilado");
        $activeSheet->setCellValue("T{$index}", "Impuesto a las Ventas del Arroz Pilado");
        $activeSheet->setCellValue("U{$index}", "Otros conceptos");
        $activeSheet->setCellValue("V{$index}", "Importe Total del Comprobante de Pago");
        $activeSheet->setCellValue("W{$index}", "ESTADO");
        $activeSheet->setCellValue("X{$index}", "ESTADO SUNAT");


        $id = 1;
        $total = 0;
        $top_gravadas = 0;
        $top_igv_monto = 0;
        $index++;// agreamos 1 fila más
        foreach ($ventas as $v) {
            $activeSheet->setCellValue("A{$index}", $id);
            $activeSheet->setCellValue("B{$index}", $v->fecha_venta->format("d/m/Y"));
            $activeSheet->setCellValue("C{$index}", $v->fecha_venta->format("d/m/Y"));

            //DOCUMENTO TIPO

            $activeSheet->setCellValue("D{$index}", $v->documento_tipo);

            $codigo_doc_tipo = ($v->documento_tipo == 'BOLETA') ? '03' : '01';
            $activeSheet->setCellValue("E{$index}", $codigo_doc_tipo);
            $activeSheet->setCellValue("F{$index}", $v->documento_serie);
            $activeSheet->setCellValue("G{$index}", $v->documento_correlativo);

            //TIPO DE DOCUEMNTO DE CLIENTE
            if ($v->cliente_doc_tipo == '01' || $v->cliente_doc_tipo == '1' || $v->cliente_doc_tipo == 01 || $v->cliente_doc_tipo == 1) {
                $activeSheet->setCellValue("H{$index}", "DNI");
            } else if ($v->cliente_doc_tipo == '06' || $v->cliente_doc_tipo == '6') {
                $activeSheet->setCellValue("H{$index}", "RUC");
            } else {
                $activeSheet->setCellValue("H{$index}", $v->cliente_doc_tipo);
            }

            $activeSheet->setCellValue("I{$index}", $v->cliente_doc_tipo);
            $activeSheet->setCellValue("J{$index}", $v->cliente_doc_numero);
            $activeSheet->setCellValue("K{$index}", $v->cliente_razon_social);

            $activeSheet->setCellValue("L{$index}", $v->op_gravadas);
            //
            // $activeSheet->setCellValue("M{$index}", $v->cliente_razon_social);
            $activeSheet->setCellValue("N{$index}", $v->igv_monto);
            //
            $activeSheet->setCellValue("O{$index}", $v->op_exoneradas);
            $activeSheet->setCellValue("P{$index}", $v->op_inafectas);
            //$activeSheet->setCellValue("Q{$index}", $v->);
            //$activeSheet->setCellValue("R{$index}", $v->);
            $activeSheet->setCellValue("S{$index}", $v->op_gravadas);
            //$activeSheet->setCellValue("T{$index}", $v->);
            //$activeSheet->setCellValue("U{$index}", $v->);
            $activeSheet->setCellValue("V{$index}", $v->total);
            //if($v->estado=='ACTIVO'){}
            //   else{
            // $activeSheet->getActiveSheet()->getStyle("U{$index}", $v->estado)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
            $estado = ($v->estado == 'ACTIVO') ? "" : $v->estado;
            $activeSheet->setCellValue("W{$index}", $estado);

            $activeSheet->setCellValue("X{$index}", $v->estado_sunat);

            //  }

            $top_gravadas += $v->op_gravadas;
            $top_igv_monto += $v->igv_monto;
            $total += $v->total;
            $id++;
            $index++;
        }
        $index++;   // agrega una fila
        // resumen del reporte, montos totales
        $activeSheet->setCellValue("L{$index}", $top_gravadas);
        $activeSheet->setCellValue("S{$index}", $top_gravadas);//REVISAR ESTO
        $activeSheet->setCellValue("N{$index}", $top_igv_monto);
        $activeSheet->setCellValue("V{$index}", $total);
        //$activeSheet->setCellValue("I{$index}", $total_deuda);
        //$activeSheet->setCellValue("J{$index}", $total);

        $filename = WWW_ROOT . "{$nombre}.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);
    }
    /**
     * @author Dylan Ale
     * * genera un pdf
     */
    public function reporteDiarioVentasPdf($ventas, $nombre = 'pdf_resumen_diario'){
        ini_set('memory_limit', '-1');
        if($ventas){
            $this->viewBuilder()->setLayout("none");
            $this->viewBuilder()->setVars([
                'ventas'     => $ventas,
            ]);

            $html = $this->render("reporteDiarioVentasPdf");
            $dompdf = new Dompdf();
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled',true);
            $dompdf->setOptions($options);

            $dompdf->loadHtml($html);
            $dompdf->setPaper("A4");
            $dompdf->render();
            // $dompdf->stream("pdf_resumen_diario.pdf", ['Attachment' => false ]);
            $output = $dompdf->output();
            file_put_contents( WWW_ROOT . "{$nombre}.pdf", $output);
        }
    }
    /**
     * @author Dylan Ale
     * sin uso
     */
    public function reporteDiarioVentasWhatsapp($ruta_archivo){
        $whatsapp_cel = $this->getConfig('whatsapp_cel');
        if($whatsapp_cel != ''){
            $num = $this->whastapp_ctrl->verfNumero($whatsapp_cel);
            $finfo= new \finfo(FILEINFO_MIME_TYPE);
            $mimetype = $finfo->file($ruta_archivo);
            $nuevadata = ['number'=> $num, 'file'=> curl_file_create($ruta_archivo, $mimetype, $ruta_archivo )];
            $url = $this->whastapp_ctrl->get_url_file() . "?token=" . $this->whastapp_ctrl->get_token();
            $this->whastapp_ctrl->curl($url, $nuevadata);
        }
    }
    /**
     * @author Dylan Ale
     */
    public function notificacionWhatsapp(){
        $whatsapp_cel = $this->getConfig('whatsapp_cel');
        $correo = $this->getConfig('correo');
        $fecha = date("d-m-Y");

        if($whatsapp_cel != ''){
            $num = $this->whastapp_ctrl->verfNumero($whatsapp_cel);
            $nuevadata = ['number'=> $num, 'message'=> "Hola, tu sistema de cotizaciones ha generado tu reporte de ventas de hoy {$fecha} y ha sido enviado a tu correo {$correo}"];
            $url = $this->whastapp_ctrl->get_url_message() . "?token=" . $this->whastapp_ctrl->get_token();
            $this->whastapp_ctrl->curl($url, $nuevadata);
        }
    }

     /**
     * @author Dylan Ale
     * Genera un zip de los xml del ultimo mes (deberia de ejecutarse en el fin de cada mes para tener todos los registros)
     */
    public function zipXml($fecha_incio='',$fecha_fin=''){
        try {
            if($fecha_incio=='' || $fecha_fin==''){
                $fecha_incio = date("Y-m-01");
                $fecha_fin = date("Y-m-t");
            }
            echo $fecha_incio . "<br>" . $fecha_fin . "<br>";
            $items = $this->Ventas->find()->contain(['SunatFeFacturas'])
            ->where(['Ventas.fecha_venta >=' => $fecha_incio . " 00:00:00", 'Ventas.fecha_venta <=' => $fecha_fin . " 23:59:59" ]);

            echo json_encode($items) . "<br>";

            $emisorCtrl = new SunatFeEmisoresController();
            $emisor = $emisorCtrl->getEmisor();
            $fecha = date("Ym");
            $zip = new \ZipArchive();

            if (!is_dir(ROOT.'/webroot/media')) {
                mkdir(ROOT.'/webroot/media');
                chmod(ROOT.'/webroot/media', 0777);
            }
            if (!is_dir(ROOT.'/webroot/media/zip-resumen')) {
                mkdir(ROOT.'/webroot/media/zip-resumen');
                chmod(ROOT.'/webroot/media/zip-resumen', 0777);
            }

            $filename = "media/zip-resumen/{$emisor->ruc}_".date("Y-m-d")."_ReporteVentas.zip";
            if ($zip->open($filename, \ZipArchive::CREATE)!==TRUE) {
                exit("cannot open <$filename>\n");
            }
            foreach($items as $item){
                echo "item";
                if($item->sunat_fe_factura){
                    $zip->addFile($emisorCtrl->getBasePathFacturas() . $item->sunat_fe_factura->nombre_archivo_xml, $item->sunat_fe_factura->nombre_archivo_xml);
                    $zip->addFile($emisorCtrl->getBasePathFacturas() . $item->sunat_fe_factura->nombre_unico ."-a.pdf", $item->sunat_fe_factura->nombre_unico ."-a.pdf");
                    $zip->addFile($emisorCtrl->getBasePathFacturas() . $item->sunat_fe_factura->nombre_unico ."-ticket.pdf", $item->sunat_fe_factura->nombre_unico ."-ticket.pdf");
                }
            }
            //Cerramos el zip
            $zip->close();

            return $filename;

            //envio por correo
            // $emailCtrl = new EmailController();
            // $emailCtrl->enviarResumenZip($filename);

            // //envio por whatsapp
            // $whatsapp_cel = $this->getConfig('whatsapp_cel');
            // $whastapp_ctrl = new WhatsappController();

            // $finfo= new \finfo(FILEINFO_MIME_TYPE);
            // $mimetype = $finfo->file($filename);
            // $nuevadata = ['number'=> $whastapp_ctrl->sanearNumPeru($whatsapp_cel),'file'=> curl_file_create($filename, $mimetype, $name )];
            // $url = $whastapp_ctrl->get_url_file() . "?token=" . $whastapp_ctrl->get_token();
            // $whastapp_ctrl->curl($url, $nuevadata);

        } catch (\Throwable $th) {
            $filename = false;
        }

    }


    /**
     * @author Dylan Ale
     * FUNCION-TPROGRAMADA
     */
    public function reporteMensualContabilidad(){
        $fecha_inicio = date('Y-m-01');
        $fecha_fin = date('Y-m-t');
        $ventas = $this->Ventas->find()->where(['fecha_venta >=' => "{$fecha_inicio} 00:00:00", 'fecha_venta' => "{$fecha_fin} 23:59:59" ])->contain(['Almacenes', 'Usuarios']);

        $this->reporteDiarioVentasPdf($ventas,'pdf_resumen_mensual');
        $this->reporteDiarioVentasExcel($ventas, 'excel_resumen_mensual');

        // la funcion recibe un rang, como sera de 1 solo dia envio la misma fecha 2 veces
        $zip = $this->zipXml($fecha_inicio,  $fecha_fin);
        $zip = (!$zip) ? '': $zip;

        //3 archivos se enviaran por correo, zip el pdf y el excel
        $this->emailCtrl->enviarResumenMensual( $zip, "pdf_resumen_mensual.pdf", 'excel_resumen_mensual.xlsx');
        // $this->reporteDiarioVentasWhatsapp('excel_resumen_diario.xlsx');
        // $this->reporteDiarioVentasWhatsapp('pdf_resumen_diario.pdf');

        //por whatsapp solo se enviara una notificacion de que se envio el correo
        exit;
    }

    /**
     * @author Ali
     *
     */
    public function consultarValidezCpeDiaAnterior(){

    }


    public function index(){

        // $url    =   Configure::read("server_api_url_get_cliente_tareas_programadas");
        $url    =   Configure::read("server_api_url_get_tareas_programadas");
        $token  =   Configure::read("server_api_token");
        $emisorCtrl = new SunatFeEmisoresController();
        $emisor = $emisorCtrl->getEmisor();
        
        $result = $this->connectToServer($url,$token . "?ruc={$emisor->ruc}");
        $result_array = $result->getJson();
        $result_json = json_decode(json_encode($result_array));

        $data = null;
        if($result_json &&  $result_json->success){
            $data = $result_json->data;
        }

        $this->set('tareas_programadas',$data);
        $this->set('view_title','Tareas programadas');


    }

    public function changeStatus(){
        $estado = $this->request->getData('estado');
        $tarea_id = $this->request->getData('tarea_id');

        $data =
        [
            'estado'    =>  $estado
        ];
        $url    =   Configure::read("server_api_url_change_status_tareas_programadas");
        $token  =   Configure::read("server_api_token");
        
        $result = $this->connectToServer($url . "/{$tarea_id}",$token,'POST',$data);
        $result_array = $result->getJson();
        $result_json = json_decode(json_encode($result_array));

        if($result_json &&  !$result_json->success){
            return $this->responseWithError("Ha habido un error para cambiar el estado");
        }

        return $this->responseWithJson(null,"Se ha cambiado el estado satisfactoriamente");

    }


    public function save($tp_id=''){
        $url = Configure::read("server_api_url_save_tareas_programadas") . "/{$tp_id}";
        $token  =   Configure::read("server_api_token");

        $result_array = null;
        // $result = $this->connectToServer($url,$token);
        // $result_array = $result->getJson();

        
        if(!$result_array['success']){
            return $this->responseWithError("Ha habido un error para guardar la tarea");
        }

        return $this->responseWithJson(null,$result_array['message']);
    }


    public function delete($tp_id){

        $url_get_one = Configure::read("server_api_url_get_one_tareas_programadas");
        $url = Configure::read("server_api_url_delete_tareas_programadas") . "/{$tp_id}";
        $token  =   Configure::read("server_api_token");


        $result = $this->connectToServer($url_get_one, $token);

        if(!$result['success']){
            $this->Flash->error("No se ha encontrado la tarea solicitada");
            return $this->redirect(['action'   => 'index']);
        }

        $result = $this->connectToServer($url, $token);
        if(!$result['success']){
            $this->Flash->erro("Ha habido un error al eliminar la tarea programada");
            return $this->redirect(['action' => 'index']);
        }

        $this->Flash->success("Tarea eliminada con éxito ");
        return $this->redirect(['action' => 'index']);
    }

    private function connectToServer($url, $token, $method = 'GET', $data = null)
    {
        $httpClient = new httpClient();

        if ($method == 'GET') {
            return $httpClient->get($url, [
                'timeout' => 0,
                'redirect' => 2,
                'headers' => [
                    'Authorization' => "Token {$token}"
                ]
            ]);
        } else if ($method == 'POST') {
            return $httpClient->post($url, $data, [
                'timeout' => 0,
                'redirect' => 2,
                'headers' => [
                    'Content-Type' => 'multipart/form-data',
                    'Authorization' => "Token {$token}"
                ]
            ]);
        }
    }

    public function cron(){
        $vars = $this->getConfigs([
            "resumen_diario_hora_envio",
            "resumen_diario_hora_verificacion",
            "informe_diario_hora_generacion",
            "respaldo_comprobantes_dia",
            "respaldo_comprobantes_hora",
        ]);
        $horaHoy = date("H:i");
        $diaSemana = date("w");
        //Para resumenes diarios;
        if($horaHoy == $vars['resumen_diario_hora_envio'] ){
            echo "<p>Hoy a las: {$horaHoy} es el resumen  </p>";
        }
        if($horaHoy == $vars['resumen_diario_hora_verificacion'] ){
            echo "<p>Hoy a las: {$horaHoy} es la verificacion del resumen </p>";
        }
        //Informe diario
        if($horaHoy == $vars['informe_diario_hora_generacion'] ){
            echo "<p>Hoy a las: {$horaHoy} es el informe diario  </p>";
        }
        //Respaldo
        if($vars['respaldo_comprobantes_dia'] == $diaSemana && $vars['respaldo_comprobantes_hora'] == $horaHoy){
            echo "<p>Hoy {$diaSemana} es el respaldo hora: {$horaHoy} </p>";
        }
        exit;
    }
    
}
