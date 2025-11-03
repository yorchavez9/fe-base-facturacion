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
    public function initialize(): void
    {
        parent::initialize();

    }
    
    public function index(){

        $top_links = [];
        $top_links['title'] =   "Listado de Facturas";

        $this->set("view_title", "Facturas");
        $this->set("top_links", $top_links);
        
    }
}