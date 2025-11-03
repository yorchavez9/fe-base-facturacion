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
        try {
            // Obtener el PDF del API externa usando venta_id
            $flag_dev = $this->getConfig('flag_dev', true);
            $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://demo.profecode.com/fe-api/';
            $url = $api_base . "sunat-fe-facturas/api-imprimir-pdf-por-venta/{$venta_id}/{$formato}";

            // Usar file_get_contents para obtener la respuesta JSON del API
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'ignore_errors' => true
                ]
            ]);

            $json_response = @file_get_contents($url, false, $context);

            if ($json_response !== false) {
                // El API ya retorna JSON con el PDF en base64
                return $this->response
                    ->withType('application/json')
                    ->withStringBody($json_response);
            } else {
                $respuesta = [
                    'success'   =>  false,
                    'data'      =>  "Error al conectar con el servidor de facturación.",
                    'message'   =>  'Error de conexión con API'
                ];
                return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode($respuesta));
            }
        } catch (\Exception $e) {
            $respuesta = [
                'success'   =>  false,
                'data'      =>  "Error: " . $e->getMessage(),
                'message'   =>  'Error de conexión'
            ];
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode($respuesta));
        }
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
        // Redirigir al API externa usando el endpoint que busca por venta_id
        $flag_dev = $this->getConfig('flag_dev', true);
        $api_base = $flag_dev ? 'http://localhost/fe-api/' : 'https://demo.profecode.com/fe-api/';
        $url = $api_base . "sunat-fe-facturas/descargar-pdf-por-venta/{$venta_id}/{$formato}";

        return $this->redirect($url);
    }
}