<?php
namespace App\Controller;
use App\Controller\AppController;
/**
 * ReporteInterno Controller
 *
 * @property \App\Model\Table\StockTable $Stock
 */
class ReporteInternoController extends AppController{
    protected $SunatFeResumenDiariosCtrl ;
    protected $ApiCtrl ;
    public function initialize(): void {
        parent::initialize();        
        $this->Authentication->allowUnauthenticated([
            'comprobantes',
        ]);
        $this->SunatFeResumenDiariosCtrl = new SunatFeResumenDiariosController();
        $this->ApiCtrl = new ApiController();
    }

    //Obtiene los registros de ventas con sus tablas relacionadas del dia anterior a cuando se llama esta funcion
    public function comprobantes(){
        $this->loadModel("Ventas");
        $this->loadModel("SunatFeEmisores");
        $respuesta = [
            'success' => false,
            'data' => '',
            'message' => 'Sin datos',
        ];
        try {
            $fecha = date("Y-m-d",strtotime(date("Y-m-d")."-1 days"));
            $ventas = $this->Ventas->find()
            ->where(['fecha_venta >=' => "{$fecha} 00:00:00", 'fecha_venta <=' => "{$fecha} 23:59:59",])
            ->contain(['VentaRegistros', 'SunatFeFacturas']);
            $emisor = $this->SunatFeEmisores->find()->first();
            //Obtiene los resumen-diarios de la fecha enviada, devuelve un array de resumenes cada uno con el query (ya sea enviado, aceptado o error) 
            //ademas devuelve  detalles del numero de boletas, notas pero solo de los resumen aceptados
            $resumen_diario_dia_anterior = $this->SunatFeResumenDiariosCtrl->getResumenDiariosDiaAnteriorDetalles($fecha);
            //obtiene informacion de la facturacion en rango de fecha, si no se envia se usara la fecha actual
            $info_facturacion = $this->ApiCtrl->getInfoFacturacionByDate($fecha, $fecha);

            if($ventas){
                $respuesta = [
                    'success' => true,
                    'data' => [
                        'ventas' => $ventas,
                        'emisor' => $emisor,
                        'resumen_diario_dia_anterior' => $resumen_diario_dia_anterior,
                        'informacion_facturacion' => $info_facturacion

                        ],
                    'message' => 'Busqueda Exitosa',
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
}