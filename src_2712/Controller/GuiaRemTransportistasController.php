<?php

namespace App\Controller;

class GuiaRemTransportistasController extends AppController
{
    private $traslado_motivos = [
        "01" => "VENTA",
        "14" => "VENTA SUJETA A CONFIRMACION DEL COMPRADOR",
        "02" => "COMPRA",
        "04" => "TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA",
        "18" => "TRASLADO EMISOR ITINERANTE CP",
        // "8" => "IMPORTACIÓN",
        // "9" => "EXPORTACIÓN",
        "19" => "TRASLADO A ZONA PRIMARIA",
        "13" => "OTROS",
    ];
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index(){

        $top_links = [];
        $top_links['title'] =   "Lista de Guías Transportista";
        $top_links['links'] = [
            'btnNuevo'  =>  [
                'name'  =>  "<i class='fas fa-plus fa-fw'></i> Nueva Guía",
                'params'    =>  ['action' => 'nueva-guia-transportista']
            ]
        ];

        $this->set("view_title", "Guias de Remisión Transportista");
        $this->set("top_links", $top_links);
        $this->set('motivo_traslado', $this->traslado_motivos);
        
    }

    public function nuevaGuiaTransportista(){
        $this->set('motivo_traslado', $this->traslado_motivos);

        $top_links = [
            'title' => 'Nueva Guía de Remisión Transportista',
            'links' => [
                'btnAdd' => [
                    'name' => '<i class="fa fa-fw fa-database fa-fw"></i> Guías de Remisión',
                    'params' => [
                        'action' => 'index'
                    ],
                ],
            ],
        ];
        $this->set(compact('top_links'));
        $this->set('view_title', 'Nueva Guía');
        $items = $this->fetchTable('Items')->find();
        foreach ($items as $it) {
            $it->value = "{$it->nombre}";
        }
        $this->set("items", $items);
        $this->set("establecimiento_default", $this->usuario_sesion['establecimiento_id']);
        

        
    }
}
