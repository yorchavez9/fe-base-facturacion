<?php

namespace App\Controller;

use App\Controller\AppController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;

class ReportesController extends AppController
{


    protected $monedas =
    [
        'USD', 'PEN'
    ];

    protected $Ventas = null;
    protected $VentaRegistros = null;
    protected $Usuarios = null;
    protected $Personas = null;
    protected $ItemCategorias = null;
    protected $ItemMarcas = null;
    protected $Items = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->Ventas = $this->fetchTable("Ventas");
        $this->VentaRegistros = $this->fetchTable("VentaRegistros");
        $this->Usuarios = $this->fetchTable("Usuarios");
        $this->Personas = $this->fetchTable('Personas');
        $this->ItemCategorias = $this->fetchTable("ItemCategorias");
        $this->ItemMarcas = $this->fetchTable("ItemMarcas");
        $this->Items = $this->fetchTable("Items");

        $this->loadComponent("ExcelHelper");
    }

    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        parent::beforeRender($event);
        $this->loadTemplateFiles();
    }

    public function index()
    {

        //FC:Menu Header
        $top_links = [
            'title' => 'Reportes',
            'links' => [

                'btnProductosVendidos' => [
                    'name' => '<i class="fa fa-fw fa-database"></i>    Productos Vendidos',
                    'params' => [

                        'action' => 'productos-vendidos'
                    ],
                ],
                // 'btnReporteComprobantes' => [
                //     'name' => '<i class="fa fa-fw fa-database"></i>   Reporte de Comprobantes',
                //     'params' => [

                //         'action' => 'txt-reporte-comprobantes'
                //     ],
                // ]

            ],
        ];
        $this->set(compact('top_links'));
        $this->set('view_title', 'Reportes');
        //FC:End Menu Header

    }

    public function productosVendidos()
    {
        $usuarios  = $this->Usuarios->find("list");

        $fini = $this->request->getQuery("fecha_ini");
        $fini = ($fini == '') ? date("Y-m-d", strtotime("-1 week", time())) : $fini;
        $ffin = $this->request->getQuery("fecha_fin");
        $ffin = ($ffin == '') ? date("Y-m-d") : $ffin;

        $aid  = $this->request->getQuery("aid", "");
        $uid  = $this->request->getQuery("uid", "");
        $exp  = $this->request->getQuery("exportar");

        $ventaregistros = $this->VentaRegistros->find()->contain(['Ventas'])->where([
            'Ventas.fecha_venta >= ' => $fini . " 00:00:00",
            'Ventas.fecha_venta <= ' => $ffin . " 23:59:59",
        ]);

        if ($uid != "") $ventaregistros = $ventaregistros->andWhere(['Ventas.usuario_id' => $uid]);

        $total_items = clone $ventaregistros;
        $total_items = $total_items->count();


        if ($exp == "excel") {

            $rep_usuario = $this->Usuarios->find()->where(['id' => $uid])->first();
            $rep_usuario_nombre = ($rep_usuario) ? $rep_usuario->nombre : "Todos los Usuarios";

            return $this->exportarExcel($ventaregistros, [
                'Fecha Inicio'      =>  $fini,
                'Fecha Fin'         =>  $ffin,
                'Almacen/Tienda'    =>  '-',
                "Usuario/Ventas"    =>  $rep_usuario_nombre,
            ]);
        }
        $ventaregistros = $this->paginate($ventaregistros);
        $this->set(compact('ventaregistros'));

        $top_links = [
            'title' => 'Reportes de Productos Vendidos',
        ];
        $this->set(compact('top_links'));
        $this->set("view_title", "Reporte e Historial de Ventas");
        $this->set(compact( 'usuarios', 'fini', 'ffin', 'aid', 'uid', 'total_items'));
    }


    public function exportarExcel($ventaregistros, $headers = [])
    {
        //$data = $this->request->getData();
        //$ventaregistros = $ventaregistros->contain(['Ventas']);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Venta de Productos");
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);

        $index = 2;

        // imprimimos los parámetros del reporte
        foreach ($headers as $k => $v) {
            $activeSheet->getStyle("A{$index}")->getFont()->setBold(true);
            $this->ExcelHelper->setMassiveExcelCellValue($activeSheet, $index, [
                $k,
                $v
            ]);
            //$activeSheet->setCellValue("A{$index}", $k);
            //$activeSheet->setCellValue("B{$index}", $v);
            $index++;  // incrmentamos 1 fila
        }

        $index++;  // incrmentamos en 1 fila


        // formato de las cabeceras del excel
        $activeSheet->getStyle("A{$index}:L{$index}")->getFont()->setBold(true);
        $this->ExcelHelper->setMassiveExcelColumnDimension($activeSheet, [20, 20, 10, 10, 30, 50, 15, 70, 20, 20, 10, 15]);

        $this->ExcelHelper->setMassiveCellBorder($activeSheet, 12, $index);
        $this->ExcelHelper->setMassiveExcelCellValue($activeSheet, $index, [
            "Código",
            "Fecha",
            "Hora",
            "Serie",
            "Nº BOLETA/FACTURA",

            "Cliente",
            "DNI",

            "Producto",
            "Cantidad",
            "Precio Unitario",
            "IGV",
            "Precio Total",
        ]);

        $cantidad = 0;
        $subtotales = 0;
        $totales = 0;
        $index++; // agreamos 1 fila más

        foreach ($ventaregistros as $v) {
            $documento_numero = isset($v->Venta) ? $v->Venta->documento_serie . "-" . $v->Venta->documento_correlativo : '';
            $this->ExcelHelper->setMassiveCellBorder($activeSheet, 12, $index);
            $this->ExcelHelper->setMassiveExcelCellValue($activeSheet, $index, [
                $v->item_codigo,
                Date("Y-m-d", strtotime($v->Venta->fecha_venta)),
                Date('H:i:s', strtotime($v->Venta->fecha_venta)),
                $v->Venta ? $v->Venta->documento_serie : "",
                $documento_numero,
                $v->Venta->cliente_razon_social,
                $v->Venta->cliente_doc_numero,

                $v->item_nombre,
                $v->cantidad,
                $v->precio_uventa,
                $v->igv_monto,
                $v->precio_total
            ]);



            $cantidad += $v->cantidad;
            $subtotales += $v->precio_unitario;
            $totales += $v->precio_total;
            $index++;
        }
        $index++;   // agrega una fila


        // resumen del reporte, montos totales
        $activeSheet->setCellValue("I{$index}", $cantidad);
        // $activeSheet->setCellValue("G{$index}", $subtotales);

        $activeSheet->getStyle("K{$index}")->getFont()->setBold(true);
        $activeSheet->setCellValue("K{$index}", "TOTAL");
        $activeSheet->setCellValue("L{$index}", $totales);

        $filename = WWW_ROOT . "reporte_ventasproductos_{$this->usuario_sesion['id']}.xlsx";

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        return $this->response->withFile($filename, ['download' => true,  'name' => "reporte_de_ventas_de_productos.xlsx"]);
    }



    public function reportesGenerales()
    {

        $fecha_ini      =   $this->request->getQuery('fecha_ini', Date('Y-m-01'));
        $fecha_fin      =   $this->request->getQuery('fecha_fin', Date('Y-m-d'));
        $moneda_codigo  =   $this->request->getQuery('moneda_codigo', 'PEN');
        $moneda_simbolo =   ['PEN'  =>  'S/', 'USD'    =>  '$'];

        $calculosVentas             =   $this->calcularTotalesVentas($fecha_ini, $fecha_fin, $moneda_codigo);

        $historicos                 =   $this->getHistoricoGeneral($fecha_ini, $fecha_fin, $moneda_codigo);



        $utilidad   =   $calculosVentas['ingresos']["TOTAL_G"];
        $impuesto_pagar = floatval($calculosVentas['impuestos']['IMPUESTOS_G']);


        $this->set('fecha_ini', $fecha_ini);
        $this->set('fecha_fin', $fecha_fin);

        /**
         * Ingresos
         */
        $this->set('ventas_impuestos', $calculosVentas['impuestos']);
        $this->set('deudas_por_cobrar', $calculosVentas['deudas_por_cobrar']);
        $this->set('pagos_por_metodo_pago', $calculosVentas['metodos_pago']);
        $this->set('ingresos', $calculosVentas['ingresos']);

        /**
         * Graficos
         */
        $this->set('ventas', $historicos['ventas']);

        /**
         * Utilidad
         */
        $this->set('utilidad', $utilidad);
        /**
         * Impuesto a pagar
         */
        $this->set('impuesto_pagar', $impuesto_pagar);

        $this->set('metodos_pago', $this->metodos_pago);
        $this->set('moneda_codigo', $moneda_codigo);
        $this->set('moneda_simbolo', $moneda_simbolo[$moneda_codigo]);

        $this->set('view_title', 'Reportes Generales');
    }


    private function calcularTotalesVentas($fecha_inicio, $fecha_fin, $moneda)
    {

        $allSales = $this->Ventas
            ->find()
            ->where(
                [
                    'fecha_venta >='    => $fecha_inicio . " 00:00:00 ",
                    'fecha_venta <='    =>  $fecha_fin . " 23:59:59 ",
                    'tipo_moneda'       =>  $moneda
                ]
            )->contain(['VentaPagos']);

        $totalIncomes =
            [
                "TOTAL_B"       =>  0, //TOTAL BOLETAS SOLES

                "TOTAL_F"       =>  0, //TOTAL FACTURAS SOLES

                "TOTAL_NV"      =>  0, //TOTAL NOTAS DE VENTA SOLES
                "TOTAL_G"       =>  0, //TOTAL GENERAL SOLES
            ];

        $totalTaxes =
            [
                "IMPUESTOS_B"   =>  0, //TOTAL DE IMPUESTOS EN VENTAS SOLES
                "IMPUESTOS_F"    =>  0, //TOTAL DE IMPUESTOS ES FACTURAS SOLES
                "IMPUESTOS_G"   =>  0, //TOTAL IMPUESTOS SOLES

            ];

        $totalPorMetodoPago =  $this->getMetodosPagoArray();

        $totalDeudasPorCobrar =
            [
                "TOTAL"   =>  0
            ];

        foreach ($allSales as $sale) {

            if ($sale->documento_tipo == 'BOLETA') {

                $totalIncomes["TOTAL_B"] += $sale->total;
                $totalTaxes["IMPUESTOS_B"] +=  $sale->igv_monto;
                $totalTaxes["IMPUESTOS_G"] +=  $sale->igv_monto;
            } elseif ($sale->documento_tipo == 'FACTURA') {

                $totalIncomes["TOTAL_F"] += $sale->total;
                $totalTaxes["IMPUESTOS_F"] +=  $sale->igv_monto;
                $totalTaxes["IMPUESTOS_G"] +=  $sale->igv_monto;
            } elseif ($sale->documento_tipo == 'NOTAVENTA') {

                $totalIncomes["TOTAL_NV"] += $sale->total;
            }
            $totalIncomes["TOTAL_G"] += $sale->total;
            foreach ($this->metodos_pago as $mtd) {
                $totalPorMetodoPago["{$mtd}"] +=  $this->getTotalPagos($sale->venta_pagos, $mtd);
            }

            $totalDeudasPorCobrar["TOTAL"]   +=  $sale->total_deuda;
        }


        return ['ingresos'  =>  $totalIncomes, 'impuestos'   =>  $totalTaxes, 'metodos_pago' =>  $totalPorMetodoPago, 'deudas_por_cobrar'    =>  $totalDeudasPorCobrar];
    }

    private function getMetodosPagoArray()
    {
        $arrayPorMetodoPago =   [];

        foreach ($this->metodos_pago as $mtd) {
            $arrayPorMetodoPago["{$mtd}"]   =   0;
        }
        return $arrayPorMetodoPago;
    }

    private function getTotalPagos($ventaPagos, $metodoPago)
    {

        $totalCant  =   0;

        if (count($ventaPagos) == 0 || gettype($ventaPagos) != 'array') {
            return $totalCant;
        }

        foreach ($ventaPagos as $pago) {
            if ($pago->medio_pago == $metodoPago && $pago->estado = 'PAGADO') {
                $totalCant += $pago->monto;
            }
        }

        return $totalCant;
    }


    private function getHistoricoGeneral($fecha_inicio, $fecha_fin, $moneda)
    {
        /**
         * Extraemos las ventas correspondientes
         */

        $ventas = $this->Ventas->find()
            ->where(
                [
                    'fecha_venta >=' => $fecha_inicio . " 00:00-00:00",
                    'fecha_venta <=' => $fecha_fin . " 23:59:59",
                    "estado !=" => "ANULADO",
                    'tipo_moneda'    => $moneda
                ]
            );
        $ventas = $ventas
            ->select(
                [
                    'total' => $ventas->func()->sum('Ventas.total'),
                    'fecha' => 'DATE(Ventas.fecha_venta)'
                ]
            )
            ->group(['DATE(Ventas.fecha_venta)']);

        return ['ventas'    =>  $ventas];
    }

    public function testList()
    {
    }


    public function reporteComprasVentasContador()
    {

        /**
         * Modelos
         */

        $periodo = $this->request->getQuery('periodo');
        $periodo = $periodo == null ? date('Y-m') : (substr($periodo, 0, 4) . "-" . substr($periodo, -2));
        $periodo = date('Y-m', strtotime($periodo));

        $f_inicio = date("{$periodo}-01");
        $f_fin = date("Y-m-t", strtotime("{$periodo}-01"));




        $ventas = $this->Ventas
            ->find()
            ->contain(['VentaRegistros', 'VentaPagos'])
            ->where(
                [
                    'fecha_venta >=' => "{$f_inicio} 00:00:00",
                    'fecha_venta <=' => "{$f_fin} 23:59:59",
                    'OR' => [
                        ['documento_tipo' => 'BOLETA'],
                        ['documento_tipo' => 'FACTURA']
                    ]
                ]
            );

        $totalesVentas = $this->getTotalesVentasByDataset($ventas, $this->monedas);

        $dataset = [
            'ventas'    =>  $ventas,
            'totales'   =>  [
                'totales_ventas'    =>  $totalesVentas,
            ]
        ];

        return $this->exportarTotalesGastosVentasCompras($dataset,  $f_inicio, $f_fin);
    }

    public function exportarTotalesGastosVentasCompras($datasets,  $f_inicio = '', $f_fin ='')
    {

        // Nueva version de exportar en base al nuevo formato
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Gerware")
            ->setTitle("Reporte de Ventas");
        $activeSheet = $spreadsheet->setActiveSheetIndex(0);
        $index = 2;



        $activeSheet->setCellValue("A{$index}", "F. Inicio");
        $activeSheet->setCellValue("B{$index}", $f_inicio);
        $index++;  // incrmentamos en 1 fila
        $activeSheet->setCellValue("A{$index}", "F. Fin");
        $activeSheet->setCellValue("B{$index}", $f_fin);

        $index++;  // incrmentamos en 1 fila
        $index++;  // incrmentamos en 1 fila

        $activeSheet->getStyle("A{$index}")->getFont()->setBold(true);
        $activeSheet->setCellValue("A{$index}", "VENTAS");
        $index++;  // incrmentamos en 1 fila
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
        foreach ($datasets['ventas'] as $v) {
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
        $activeSheet->setCellValue("P{$index}", $pen_top_igv_monto);
        $activeSheet->setCellValue("Q{$index}", $pen_total);

        $index++;
        $activeSheet->setCellValue("L{$index}", "Totales USD");
        $activeSheet->setCellValue("M{$index}", $usd_top_gravadas);
        $activeSheet->setCellValue("N{$index}", $usd_top_exonerados);
        $activeSheet->setCellValue("O{$index}", $usd_top_inafectos);
        $activeSheet->setCellValue("P{$index}", $usd_top_igv_monto);
        $activeSheet->setCellValue("Q{$index}", $usd_total);

        $index++;
        $index++;

        $filename = WWW_ROOT . "reporte_consolidado.xlsx";

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        $filename_to_download = "reporte_consolidado_{$f_inicio}_hasta_{$f_fin}.xlsx";

        $response = $this->response->withFile($filename, ['download' => true, 'name' => $filename_to_download]);
        return $response;
    }


    private function getTotalesVentasByDataset($allSales, $monedas)
    {
        $totalIncomes = [];
        $totalTaxes = [];
        $totalDeudasPorCobrar = [];
        foreach ($monedas as $moneda) {
            $totalIncomes["TOTAL_B_{$moneda}"]  = 0;
            $totalIncomes["TOTAL_F_{$moneda}"]  = 0;
            $totalIncomes["TOTAL_NV_{$moneda}"]  = 0;
            $totalIncomes["TOTAL_G_{$moneda}"]  = 0;

            $totalTaxes["IMPUESTOS_B_{$moneda}"] = 0;
            $totalTaxes["IMPUESTOS_F_{$moneda}"] = 0;
            $totalTaxes["IMPUESTOS_G_{$moneda}"] = 0;

            $totalDeudasPorCobrar["TOTAL_{$moneda}"] = 0;
        }

        $totalPorMetodoPago =  $this->getMetodosPagoArray();


        foreach ($allSales as $sale) {

            if ($sale->documento_tipo == 'BOLETA') {
                $totalIncomes["TOTAL_B_{$sale->tipo_moneda}"] += $sale->total;
                $totalTaxes["IMPUESTOS_B_{$sale->tipo_moneda}"] +=  $sale->igv_monto;
                $totalTaxes["IMPUESTOS_G_{$sale->tipo_moneda}"] +=  $sale->igv_monto;
            } elseif ($sale->documento_tipo == 'FACTURA') {

                $totalIncomes["TOTAL_F_{$sale->tipo_moneda}"] += $sale->total;
                $totalTaxes["IMPUESTOS_F_{$sale->tipo_moneda}"] +=  $sale->igv_monto;
                $totalTaxes["IMPUESTOS_G_{$sale->tipo_moneda}"] +=  $sale->igv_monto;
            } elseif ($sale->documento_tipo == 'NOTAVENTA') {

                $totalIncomes["TOTAL_NV_{$sale->tipo_moneda}"] += $sale->total;
            }
            $totalIncomes["TOTAL_G_{$sale->tipo_moneda}"] += $sale->total;
            foreach ($this->metodos_pago as $mtd) {
                $totalPorMetodoPago["{$mtd}"] +=  $this->getTotalPagos($sale->venta_pagos, $mtd);
            }

            $totalDeudasPorCobrar["TOTAL_{$sale->tipo_moneda}"]   +=  $sale->total_deuda;
        }

        return ['ventas'    =>  $totalIncomes, 'impuestos'  =>  $totalTaxes, 'deudas'  =>  $totalDeudasPorCobrar];
    }

    public function getActiveSheetVentas($ventas, &$activeSheet, $params = [])
    {

        $index = 2;
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
        $activeSheet->setCellValue("W{$index}", "ESTADO");
        $activeSheet->setCellValue("X{$index}", "ESTADO SUNAT");


        $id = 1;
        $total = 0;
        $top_gravadas = 0;
        $top_igv_monto = 0;
        $index++; // agreamos 1 fila más
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
        $activeSheet->setCellValue("S{$index}", $top_gravadas); //REVISAR ESTO
        $activeSheet->setCellValue("N{$index}", $top_igv_monto);
        $activeSheet->setCellValue("V{$index}", $total);
    }

    public function clientesDeudas()
    {

        $nombre = $this->request->getQuery('nombre', '');

        // $personas = $this->Personas->find()



    }

    function getFolderSize($dir) {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir)) as $file) {
            $size += $file->getSize();
        }
        return number_format($size / 1048576, 2);
    }
}
