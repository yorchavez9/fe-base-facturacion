<?php
declare(strict_types=1);

namespace App\Controller;
/**
 * SunatFeNotas Controller
 *
 * @property \App\Model\Table\SunatFeNotasTable $SunatFeNotas
 * @method \App\Model\Entity\SunatFeNota[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SunatFeNotasDebitoController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index(){
        $top_links = [
            'title' => 'Notas de Débito',
            // 'functions' =>  [
            //     'btnNuevo'  =>  [
            //         'name'  => "<i class='fas fa-plus fa-fw'></i> Nueva Nota de Débito",
            //         'function' => "ModalFacturaNota.init(`debito`)"
            //     ]
            // ]
        ];
        $this->set('view_title', 'Notas de Débito');
        $this->set(compact('top_links'));
    }

    public function view($id = 0)
    {
        $top_links = [
            'title' => "Detalle del Documento",
            'links' =>  [ ]
        ];
        $this->set(compact('top_links'));
        $this->set("view_title", "Detalle del Documento");
        $this->set("nota_id", $id);
    }

    public function nuevaNotaDebito($factura_id = 0) {
     
        $array_afectacion_igv =
            [
              '10' => 'Grav.',
              '20' => 'Exon.',
              '30' => 'Inaf.'
            ];
        $this->set(compact('array_afectacion_igv'));

      
            $view_title = "Nueva Nota de Débito";

        $top_links = [
            'title' => $view_title,
            'links' => [
                'btnNuevo' => [
                    'name' => '<i class="fa fa-chevron-left"></i> Notas',
                    'params' => [
                        'action' => 'index'
                    ],
                ],
            ],
        ];

        $this->set('factura_id', $factura_id);
        $this->set('view_title', $view_title);
        $this->set(compact('top_links'));
    }

}
