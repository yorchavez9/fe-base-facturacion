<?php
declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\See;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Detraction;
use Greenter\Model\Sale\Legend;

class SunatFeFacturasController extends AppController
{
    protected $Ventas = null;

    public function initialize(): void
    {
        parent::initialize();
        $this->Ventas = $this->fetchTable("Ventas");
    }

    public function index(){

        $top_links = [];
        $top_links['title'] =   "Listado de Facturas";

        $this->set("view_title", "Facturas");
        $this->set("top_links", $top_links);

    }

    /**
     * Método para imprimir PDF de facturas/boletas electrónicas
     * Retorna el contenido del PDF en base64 para impresión directa
     *
     * @param int $venta_id ID de la venta
     * @param string $formato Formato del PDF (a4 o ticket)
     * @return \Cake\Http\Response JSON con el PDF en base64
     */
    public function apiImprimirPdf($venta_id, $formato = 'a4'){
        $venta = $this->Ventas->get($venta_id);

        // Primero intentar buscar PDF local
        $nombre_archivo = "{$venta->documento_serie}-{$venta->documento_correlativo}-{$formato}.pdf";
        $ruta_pdf = $this->getPathPdf("ventas") . $nombre_archivo;

        if(file_exists($ruta_pdf)){
            $respuesta = [
                'success'   =>  true,
                'data'      =>  base64_encode(file_get_contents($ruta_pdf)),
                'message'   =>  'Archivo encontrado'
            ];
        } else {
            // Si no existe localmente, intentar obtenerlo del API externa
            try {
                $flag_dev = $this->getConfig('flag_dev', true);
                $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://demo.profecode.com/fe-api/';
                $url = $api_base . "sunat-fe-facturas/descargar-pdf/{$venta_id}/{$formato}";

                // Usar file_get_contents para obtener el PDF del API
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 30,
                        'ignore_errors' => true
                    ]
                ]);

                $pdf_content = @file_get_contents($url, false, $context);

                if ($pdf_content !== false && strlen($pdf_content) > 0) {
                    // Guardar el PDF localmente para futuras impresiones
                    file_put_contents($ruta_pdf, $pdf_content);

                    $respuesta = [
                        'success'   =>  true,
                        'data'      =>  base64_encode($pdf_content),
                        'message'   =>  'Archivo obtenido del API'
                    ];
                } else {
                    $respuesta = [
                        'success'   =>  false,
                        'data'      =>  "El archivo PDF no está disponible. Por favor, intente regenerarlo desde los detalles de la venta.",
                        'message'   =>  'Archivo no encontrado en API'
                    ];
                }
            } catch (\Exception $e) {
                $respuesta = [
                    'success'   =>  false,
                    'data'      =>  "Error al obtener el PDF: " . $e->getMessage(),
                    'message'   =>  'Error de conexión'
                ];
            }
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($respuesta));
    }

    /**
     * Método para descargar PDF de facturas/boletas electrónicas
     * Redirige al archivo PDF para descarga
     *
     * @param int $venta_id ID de la venta
     * @param string $formato Formato del PDF (a4 o ticket)
     * @return \Cake\Http\Response
     */
    public function descargarPdf($venta_id, $formato = 'a4'){
        $venta = $this->Ventas->get($venta_id);
        $nombre_archivo = "{$venta->documento_serie}-{$venta->documento_correlativo}-{$formato}.pdf";
        $ruta_pdf = $this->getPathPdf("ventas") . $nombre_archivo;

        if(file_exists($ruta_pdf)){
            return $this->response->withFile($ruta_pdf, [
                'download' => true,
                'name' => $nombre_archivo
            ]);
        } else {
            // Si no existe localmente, redirigir al API externa
            $flag_dev = $this->getConfig('flag_dev', true);
            $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://demo.profecode.com/fe-api/';
            $url = $api_base . "sunat-fe-facturas/descargar-pdf/{$venta_id}/{$formato}";

            return $this->redirect($url);
        }
    }
}