<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * VentaPagos Controller
 *
 * @property \App\Model\Table\VentaPagosTable $VentaPagos
 * @method \App\Model\Entity\VentaPago[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class VentaPagosController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->Ventas =  $this->fetchTable('Ventas');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($venta_id)
    {
        $venta = $this->Ventas
            ->find()
            ->where(
                [
                    'id'    =>  $venta_id
                ]
            )->first();

        if ($venta) {
            $pagos = $this->VentaPagos
                ->find()
                ->where(
                    [
                        'venta_id'  =>  $venta_id
                    ]
                );
            $flagBtn = true;
            foreach($pagos as $pp){
                if($pp->estado == 'PROGRAMADO'){
                    $flagBtn = false;
                }
            }
            $top_links =
                [
                    'title' =>  "Pagos de la venta {$venta->documento_serie}-{$venta->documento_correlativo}"
                ];
            if ($venta->total_deuda > 0 && $flagBtn) {

                $top_links['links'] = [];
                $top_links['links']['btnNuevo']    =
                    [
                        'name'  =>  "<i class='fa fa-plus fa-fw' ></i> Pago",
                        'params' =>  []
                    ];
            }

            $this->set(compact('pagos', 'venta'));
            $this->set('view_title', "Pagos");
            $this->set('top_links', $top_links);
            $this->set('metodos_pago',$this->metodos_pago);
        } else {
            $this->Flash->error('La venta especificada no existe');
            return $this->redirect(['controller'    =>  'Ventas', 'action'   =>  'index']);
        }
    }

    public function save($venta_id)
    {
        $respuesta =
            [
                'success'   =>  false,
                'data'      =>  '',
                'message'   =>  'No se han encontrado datos'
            ];


        $data = $this->request->getData();
        if ($this->request->is('post')) {
            $venta = $this->Ventas
                ->find()
                ->where(
                    [
                        'id'    =>  (int) $venta_id
                    ]
                )->first();

            if ($venta) {
                $pago_id = $data['id'] ?? '';

                $pago = $this->VentaPagos->find()->where(['id' => (int)$pago_id , 'estado' => 'PROGRAMADO'])->first();
                if(!$pago){
                    $pago = $this->VentaPagos->newEmptyEntity();
                }
                $pago = $this->VentaPagos->patchEntity($pago, $data);
                $pago->estado = 'PAGADO';
                $pago->fecha_pago = Date('Y-m-d');
                $pago->venta_id = $venta->id;
                $pago->nota = $pago->nota == '' || !$pago->nota ? 'Pagado el ' . Date('Y-m-d') : $pago->nota;
                $pago = $this->VentaPagos->save($pago);
                if ($pago) {
                    $venta->total_pagos += $pago->monto;
                    $diferencia = $venta->total_deuda - $pago->monto;
                    $venta->total_deuda = $diferencia <= 0 ? 0 : $diferencia;
                    if(!$this->Ventas->save($venta)){
                        $respuesta =
                        [
                            'success'   =>  false,
                            'data'      =>  $pago,
                            'message'   =>  'Ocurrio un error actualizando estado de la venta'
                        ];

                    }else{
                        $respuesta =
                        [
                            'success'   =>  false,
                            'data'      =>  $pago,
                            'message'   =>  'El pago ha sido registrado con éxito, estado de la venta actualizado'
                        ];

                    }
                }else{
                    $respuesta =
                        [
                            'success'   =>  false,
                            'data'      =>  $pago,
                            'message'   =>  'Ocurrio un error al guardar el Pago',
                            'a'      =>  $data,
                        ];
                }
            }else{
                $respuesta =
                [
                    'success'   =>  false,
                    'data'      =>  '',
                    'message'   =>  'No se encontro la venta relacionada'
                ];

            }
        }

        return $this->response->withType('application/json')->withStringBody(json_encode($respuesta));
    }
}
