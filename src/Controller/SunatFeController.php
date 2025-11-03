<?php
namespace App\Controller;

use App\Controller\AppController;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\Root;
use ZipArchive;
use SoapVar;
use SoapClient;
use SoapHeader;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use DateTime;
use App\Controller\NumberToLetterConverter;

class SunatFeController extends AppController
{
    /**
     * Ruta base de los archivos de facturacion
     * @var string
     */
    protected $sunat_fe_files_base = '';
    protected $Cuentas = null;
    protected $Personas = null;
    protected $Ventas = null;
    protected $VentaRegistros = null;
    public function initialize():void {
        parent::initialize();
        //$this->Auth->allow(['index']);

        $this->Cuentas = $this->fetchTable("Cuentas");
        $this->Personas = $this->fetchTable("Personas");
        $this->Ventas = $this->fetchTable("Ventas");
        $this->VentaRegistros = $this->fetchTable("VentaRegistros");


        $this->Authentication->allowUnauthenticated([
            'apiConsultaDniRuc',
        ]);


    }


    public function index(){
        echo "portal de consulta CPE";
        exit;
    }

    public function apiConsultaDniRuc()
    {
        $documento = $this->request->getQuery("documento");

        if (strlen($documento) == 8) {
            $url = "https://factura24.pe/api/consulta-doc/dni/{$documento}";

            $data = json_decode(file_get_contents($url), true);

            $nombre_completo = $data['data']['nombre'];


            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'result' => [
                        'cliente_doc_tipo' => '1',
                        'razon_social' => $nombre_completo,
                        'nombre_comercial' => $nombre_completo,
                        'apellidos' => $data['data']['apellido_paterno'] . " " . $data['data']['apellido_materno'],
                        'nombres' => $data['data']['nombres']
                    ]
                ]));
        } else {
            $url = "https://factura24.pe/api/consulta-doc/ruc/{$documento}";


            $data = json_decode(file_get_contents($url), true);
            $direccion_completa = $data['data']['direccion']." ".$data['data']['distrito']."  ".$data['data']['provincia']."  ".$data['data']['departamento'] ;
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'result' => [
                        'cliente_doc_tipo' => '6',
                        'razon_social' => $data['data']['nombre'],
                        'nombre_comercial' => $data['data']['nombre'],
                        'ruc' => $data['data']['numero_documento'],
                        'direccion' => $direccion_completa ,
                    ]
                ]));
        }

    }
}
