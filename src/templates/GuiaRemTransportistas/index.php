<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SunatFeGuiaremRemitente[]|\Cake\Collection\CollectionInterface $guiaRemRemitentes
 */
?>
<div class="guiaRemRemitentes index content">
    <div class="table-responsive gw-table">
        <table class="table table-striped table-hover table-sm">
            <thead>
                <tr class="text-primary font-weight-bold">
                    <th>Guía Remitente</th>
                    <th>Detalles</th>
                    <th>Estado</th>
                    <th class="actions">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla_guias">
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="modalImprimir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pasos para generar el PDF:   </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <ul class="list-group" id="list_modal_imprimir">
                        <li class="list-group-item"> 
                            1ro. Enviar guia a sunat
                        </li>
                        <li class="list-group-item"> 
                            2do. Obtener CDR
                            <button class="btn btn-primary btn-sm">
                                <i class='fas fa-paper-plane fa-fw'></i>
                            </button>
                        </li>
                        <li class="list-group-item"> 
                            3ro. Descargar PDF 
                            <button class="btn btn-primary btn-sm">
                                <i class='fas fa-paper-plane fa-fw'></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>