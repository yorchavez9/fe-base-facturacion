<?php

namespace App\Controller;

class ResumenDiariosController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index(){

        $top_links = [];
        $top_links['title'] =   "Lista de Resumenes Diarios";
        $top_links['functions'] = [
            'btnA' => [
                'function'  =>  'Resumenes.add()',
                'name'  =>    "<i class='fas fa-plus fa-fw'></i> Resumen" ,
            ]
        ];

        $this->set("view_title", "Resumenes Diarios");
        $this->set("top_links", $top_links);
        
    }

    public function documentos($resumen_id = ''){

        $top_links = [];
        $top_links['title'] =   "Documentos";

        $this->set("view_title", "Documentos del Resumen Diario");
        $this->set("top_links", $top_links);
        $this->set("resumen_id", $resumen_id);
        
    }
}
