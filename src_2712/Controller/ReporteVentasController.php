<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Stock Controller
 *
 * @property \App\Model\Table\StockTable $Stock
 */
class ReporteVentasController extends AppController
{

    public function initialize(): void {
        parent::initialize();
        $this->Ventas = $this->fetchTable('Ventas');
        $this->VentaRegistros = $this->fetchTable(alias: 'VentaRegistros');

        $this->loadComponent("ExcelHelper");
    }

    public function beforeRender(\Cake\Event\EventInterface $event) {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }

    public function exportar($ventas, $params = [])
    {
        //$data = $this->request->getData();
        $ventas = $ventas->contain(['Usuarios']);
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


        foreach ($params as $k => $v) {
            $activeSheet->setCellValue("A{$index}", $v['label']);
            $activeSheet->setCellValue("B{$index}", $v['value']);

            $index++;  // incrmentamos 1 fila
        }
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
        $activeSheet->getStyle("A{$index}:X{$index}")->applyFromArray($styleArrayTitulo);


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

            $codigo_doc_tipo = ($v->documento_tipo == 'BOLETA') ? '03' : (($v->documento_tipo == 'FACTURA') ? '01' : '');
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

        $filename = WWW_ROOT . "media/reporte_ventas_.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $filename_to_download = "reporte_de_ventas_desde_{$params['fecha_inicio']['value']}_hasta_{$params['fecha_fin']['value']}.xlsx";

        $response = $this->response->withFile($filename, ['download' => true, 'name' => $filename_to_download]);
        return $response;
    }

    // Nueva version de exportar en base al nuevo formato
    public function exportarVenta($ventas, $params = [])
    {
        //$data = $this->request->getData();
        $ventas = $ventas->contain(['Usuarios','VentaRegistros']);
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


        foreach ($params as $k => $v) {
            $activeSheet->setCellValue("A{$index}", $v['label']);
            $activeSheet->setCellValue("B{$index}", $v['value']);

            $index++;  // incrmentamos 1 fila
        }
        $index++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:W{$index}")->getFont()->setBold(true);

        $activeSheet->getColumnDimension('A')->setAutoSize(true);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);
        $activeSheet->getColumnDimension('I')->setAutoSize(true);
        $activeSheet->getColumnDimension('J')->setAutoSize(true);
        $activeSheet->getColumnDimension('K')->setAutoSize(true);
        $activeSheet->getColumnDimension('L')->setAutoSize(true);
        $activeSheet->getColumnDimension('M')->setAutoSize(true);
        $activeSheet->getColumnDimension('N')->setAutoSize(true);
        $activeSheet->getColumnDimension('O')->setAutoSize(true);
        $activeSheet->getColumnDimension('P')->setAutoSize(true);
        $activeSheet->getColumnDimension('Q')->setAutoSize(true);
        // $activeSheet->getStyle("A{$index}:Q{$index}")->applyFromArray($styleArrayTitulo);
        $this->ExcelHelper->setMassiveCellBorder($activeSheet,17,$index);


        $activeSheet->setCellValue("A{$index}", "Nro");
        $activeSheet->setCellValue("B{$index}", "Tipo Doc");
        $activeSheet->setCellValue("C{$index}", "Fecha Emision");
        $activeSheet->setCellValue("D{$index}", "Productos");
        $activeSheet->setCellValue("E{$index}", "Cant. Items");
        $activeSheet->setCellValue("F{$index}", "Tipo de comprobante");

        $activeSheet->setCellValue("G{$index}", "Número");

        $activeSheet->setCellValue("H{$index}", "Doc. Cliente");
        $activeSheet->setCellValue("I{$index}", "Cliente");

        $activeSheet->setCellValue("J{$index}", "Estado");
        $activeSheet->setCellValue("K{$index}", "Estado Sunat");
        $activeSheet->setCellValue("L{$index}", "Moneda");
        $activeSheet->setCellValue("M{$index}", "Total Gravado");
        $activeSheet->setCellValue("N{$index}", "Total Exonerado");
        $activeSheet->setCellValue("O{$index}", "Total Inafecto");
        $activeSheet->setCellValue("P{$index}", "Total IGV");
        $activeSheet->setCellValue("Q{$index}", "Total");


        $id = 1;
        $pen_total = 0;
        $pen_top_gravadas = 0;
        $pen_top_exonerados = 0;
        $pen_top_inafectos = 0;
        $pen_top_igv_monto = 0;

        $usd_total = 0;
        $usd_top_gravadas = 0;
        $usd_top_exonerados = 0;
        $usd_top_inafectos = 0;
        $usd_top_igv_monto = 0;

        $index++;// agreamos 1 fila más
        foreach ($ventas as $v) {
            $this->ExcelHelper->setMassiveCellBorder($activeSheet,17,$index);
            if($v->estado == 'ANULADO' || $v->estado_sunat == 'ANULADO')
            {
                $activeSheet->getStyle("A{$index}:P{$index}")
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                   ->setRGB('FF5151');
            }
            $activeSheet->setCellValue("A{$index}", $id);
            $activeSheet->setCellValue("B{$index}", $v->documento_tipo);
            $activeSheet->setCellValue("C{$index}", $v->fecha_venta->format("d/m/Y"));

            //DOCUMENTO TIPO

            $array_productos = [];
            $contador = 0;
            foreach($v->venta_registros as $reg)
            {
                $contador += isset($reg->cantidad) ? floatval($reg->cantidad): 0;
                $array_productos[] = "{$reg->item_nombre} x {$reg->cantidad}";
            }
            $activeSheet->setCellValue("D{$index}", implode(' , ',$array_productos));

            $activeSheet->setCellValue("E{$index}", $contador);//FALTA MODIFICAR

            $activeSheet->setCellValue("F{$index}", $v->documento_tipo);

            $codigo_doc_tipo = ($v->documento_tipo == 'BOLETA') ? '03' : (($v->documento_tipo == 'FACTURA') ? '01' : '');
            $activeSheet->setCellValue("G{$index}", "{$v->documento_serie}-{$v->documento_correlativo}");
            $activeSheet->setCellValue("H{$index}", $v->cliente_doc_numero);
            $activeSheet->setCellValue("I{$index}", $v->cliente_razon_social);

            $estado = ($v->estado == 'ACTIVO') ? "" : $v->estado;
            $activeSheet->setCellValue("J{$index}", $estado);
            $activeSheet->setCellValue("K{$index}", $v->estado_sunat);


            $activeSheet->setCellValue("L{$index}", $v->tipo_moneda);
            $activeSheet->setCellValue("M{$index}", $v->op_gravadas);

            $activeSheet->setCellValue("N{$index}", $v->op_exoneradas);
            $activeSheet->setCellValue("O{$index}", $v->op_inafectas);
            $activeSheet->setCellValue("P{$index}", $v->igv_monto);

            $activeSheet->setCellValue("Q{$index}", $v->total);
            //if($v->estado=='ACTIVO'){}
            //   else{
            // $activeSheet->getActiveSheet()->getStyle("U{$index}", $v->estado)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('000000');

            //  }
            if($v->tipo_moneda == 'PEN' && !($v->estado == 'ANULADO' || $v->estado_sunat == 'ANULADO'))
            {
                $pen_top_gravadas += $v->op_gravadas;
                $pen_top_exonerados += $v->op_exoneradas;
                $pen_top_inafectos +=  $v->op_inafectas;
                $pen_top_igv_monto += $v->igv_monto;
                $pen_total += $v->total;
            }else{
                $usd_top_gravadas += $v->op_gravadas;
                $usd_top_exonerados += $v->op_exoneradas;
                $usd_top_inafectos +=  $v->op_inafectas;
                $usd_top_igv_monto += $v->igv_monto;
                $usd_total += $v->total;
            }
//            $total += $v->total;
            $id++;
            $index++;
        }
        $index++;   // agrega una fila
        // resumen del reporte, montos totales

        $activeSheet->setCellValue("L{$index}", "Totales PEN");
        $activeSheet->setCellValue("M{$index}", $pen_top_gravadas);
        $activeSheet->setCellValue("N{$index}", $pen_top_exonerados);
        $activeSheet->setCellValue("O{$index}", $pen_top_inafectos);
//        $activeSheet->setCellValue("S{$index}", $top_gravadas);//REVISAR ESTO
        $activeSheet->setCellValue("P{$index}", $pen_top_igv_monto);
        $activeSheet->setCellValue("Q{$index}", $pen_total);

        $index++;
        $activeSheet->setCellValue("L{$index}", "Totales USD");
        $activeSheet->setCellValue("M{$index}", $usd_top_gravadas);
        $activeSheet->setCellValue("N{$index}", $usd_top_exonerados);
        $activeSheet->setCellValue("O{$index}", $usd_top_inafectos);
//        $activeSheet->setCellValue("S{$index}", $top_gravadas);//REVISAR ESTO
        $activeSheet->setCellValue("P{$index}", $usd_top_igv_monto);
        $activeSheet->setCellValue("Q{$index}", $usd_total);



        //$activeSheet->setCellValue("I{$index}", $total_deuda);
        //$activeSheet->setCellValue("J{$index}", $total);

//        $filename = WWW_ROOT . "reporte_ventas_.xlsx";

        $emisorCtrl = new SunatFeEmisoresController();
        $filename = $emisorCtrl->getBasePathMedia()."reporte_ventas_.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $filename_to_download = "reporte_de_ventas_desde_{$params['fecha_inicio']['value']}_hasta_{$params['fecha_fin']['value']}.xlsx";

        $response = $this->response->withFile($filename, ['download' => true, 'name' => $filename_to_download]);
        return $response;

    }


    public function generarReporteComprobantes(){
        $fecha_actual = Date('Y-m-d');

        $ventas_fecha = $this->Ventas->find()->where(['fecha_venta LIKE'=> "%{$fecha_actual}%"]);







    }

    public function generarReporteProductos(){
        $fecha_actual = Date('Y-m-d');

        $items_ventas = $this->VentaRegistros->find()
            ->innerJoinWith(
            'Ventas',function($q) use ($fecha_actual){
            return $q->where(['Ventas.fecha_venta LIKE'=> "%{$fecha_actual}%"]);
        }
        );


        $items_ventas = $items_ventas
            ->select(
                    [
                        'item_id' =>'VentaRegistros.item_id'
                        ,'item_codigo' => 'VentaRegistros.item_codigo'
                        ,'item_nombre' =>'VentaRegistros.item_nombre'
                        ,'item_precio_uventa' =>'VentaRegistros.precio_uventa'
                        ,'item_cantidad_vendida' => 'SUM(VentaRegistros.cantidad)'
                        , 'fecha_venta' => ' Ventas.fecha_venta'
                ])
        ->group('item_id')
        ;


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Ventas");
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);
        $index = 2;
        // imprimimos los parámetros del reporte


        $styleArrayTitulo = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['HEX' => '#B7BFBA'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];

        $styleArrayBody=[
            'alignment' => [
                'horizontal' =>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ]
        ];

        $index++;  // incrmentamos en 1 fila
        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:W{$index}")->getFont()->setBold(true);

        $activeSheet->getColumnDimension('A')->setWidth(30);
        $activeSheet->getColumnDimension('B')->setWidth(30);
        $activeSheet->getColumnDimension('C')->setWidth(30);
        $activeSheet->getColumnDimension('D')->setWidth(30);
        $activeSheet->getColumnDimension('E')->setWidth(30);
        $activeSheet->getColumnDimension('F')->setWidth(15);

        $activeSheet->getStyle('A3:G3')->applyFromArray($styleArrayTitulo);


        $activeSheet->setCellValue("A{$index}", "Producto Id");
        $activeSheet->setCellValue("B{$index}", "Producto Codigo");
        $activeSheet->setCellValue("C{$index}", "Producto Nombre");
        $activeSheet->setCellValue("D{$index}", "Producto Precio de Venta");

        $activeSheet->setCellValue("E{$index}", "Cantidad Vendida");
        $activeSheet->setCellValue("F{$index}", "Fecha");



        $index++;// agreamos 1 fila más
        foreach ($items_ventas as $v) {
            $activeSheet->getStyle("A{$index}:F{$index}")->applyFromArray($styleArrayBody);
            $activeSheet->setCellValue("A{$index}", $v->item_id);
            $activeSheet->setCellValue("B{$index}", $v->item_codigo);
            $activeSheet->setCellValue("C{$index}", $v->item_nombre);

            //DOCUMENTO TIPO

            $activeSheet->setCellValue("D{$index}", $v->item_precio_uventa);

            $activeSheet->setCellValue("E{$index}", $v->item_cantidad_vendida);
            $activeSheet->setCellValue("F{$index}", $fecha_actual);

            $index++;
        }
        $index++;   // agrega una fila


        $filename = WWW_ROOT . "media/reporte_ventas_.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $filename_to_download = "reporte_de_ventas_productos_de_{$fecha_actual}.xlsx";

        $response = $this->response->withFile($filename, ['download' => true, 'name' => $filename_to_download]);
        return $response;




    }

}
