<form class="form">
    <?php
    $this->Form->setTemplates(['inputContainer' => '{{content}}'])
    ?>
    <div class="row px-3">
        <div class="col-md-12 col-lg-4 px-1">
            <label class="control-label">Rango de Fechas</label>
            <div class="form-group px-1 mb-0">
                <div class="row px-2">
                    <div class="col-md-6 px-1 mb-3">
                        <input class="form-control form-control-sm text-center fecha" name="fecha_ini" value="<?= $fini ?>">
                    </div>
                    <div class="col-md-6 px-1 mb-3">
                        <input class="form-control form-control-sm text-center fecha" name="fecha_fin" value="<?= $ffin ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-lg-5 col-xl-2  px-1 mb-3">
            <label>Nombre</label>
            <input type="text" name="opt_nombres" value="<?=$opt_nombres?>" class="form-control form-control-sm" placeholder="Nombre" autocomplete="off">
        </div>
        <div class="col-md-4 col-lg-3 col-xl col-lg px-1 mb-3">
            <label>Estado Pago</label>
            <?= $this->Form->Control("opt_estado_pago", [
                'label' => false,
                'options' => ['' => 'TODOS','PAGADO' => 'PAGADO', 'DEUDA' => 'DEUDA'],
                'empty' => '- Todos -',
                'value' => $opt_estado_pago,
                'class' => 'form-control form-control-sm'
            ]) ?>
        </div>

        <div class="col-md-4 col-lg-3 col-xl-2 px-1"  style="max-width:150px" >
            <label class="control-label">&nbsp;</label><br/>
            <div class="input-group">
                <button type="submit" class="btn btn-sm btn-primary mx-1">
                    <i class="fa fa-fw fa-search"></i>
                </button>
                <button type="button" onclick="abrirModalFactura()" class="btn btn-sm btn-primary mx-1">
                    Doc.
                </button>
                <div class="input-group-append">
                    <button type="submit" name="exportar" value="1" class="btn btn-sm btn-outline-primary mx-1">
                        <i class="fa fa-fw fa-file-excel"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modalTiposDoc">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tipos de Documento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="BOLETA" value="BOLETA" class="form-check-input" id="exampleCheck1" <?= $opt_boleta == "BOLETA" ? "checked" : "" ?> >
                                <label class="form-check-label" for="exampleCheck1">BOLETAS</label>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="FACTURA" value="FACTURA" class="form-check-input" id="exampleCheck2" <?= $opt_factura == "FACTURA" ? "checked" : "" ?> >
                                <label class="form-check-label" for="exampleCheck2">FACTURAS</label>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="NOTAVENTA" value="NOTAVENTA" class="form-check-input" id="exampleCheck3" <?= $opt_notaventa == "NOTAVENTA" ? "checked" : "" ?> >
                                <label class="form-check-label" for="exampleCheck3">NOTAVENTAS</label>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col text-right">
                            <button type="submit" id="btn_doc_tipos" class="btn btn-primary"><i class="fa fa-fw fa-search"></i> Buscar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</form>
<div class="row pt-2">
    <div class="col-md-12">
        <div class="table-responsive gw-table" >
            <table class="table table-striped table-hover table-sm " >
            <thead>
            <tr>
                <th style="width: 150px;">
                    <?=$this->Paginator->sort('fecha_venta', ['label'=>'Fecha / Hora / ID'])?>
                </th>
                <th><?=$this->Paginator->sort('cliente_id', ['label'=>'Cliente'])?></th>
                <!-- <th style="width: 150px;"><?=$this->Paginator->sort('documento_tipo', ['label'=>'Documento'])?></th> -->
                <th style="width: 200px;" colspan="2">Totales</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($ventas as $v):

                echo $this->Element('global_fila_ventas',['v' => $v]);

            endforeach;?>
            </tbody>
        </table>
        </div>
        <div class="paginator text-center">
            <?= $this->element("paginador"); ?>
            <div class="alert alert-info text-center">

                    Total en Ventas S/<?= $this->Number->format($totales_ventas['pen_total'],['precision' =>2,'places' =>2]) ?>
                    Total pagado S/<?= $this->Number->format($totales_ventas['pen_pagos'],['precision' =>2,'places' =>2]) ?>
                    Total en Crédito S/<?= $this->Number->format($totales_ventas['pen_deuda'],['precision' =>2,'places' =>2]) ?>
                    <br>
                    Total en Ventas USD <?= $this->Number->format($totales_ventas['usd_total'],['precision' =>2,'places' =>2]) ?>
                    Total pagado USD <?= $this->Number->format($totales_ventas['usd_pagos'],['precision' =>2,'places' =>2])?>
                    Total en Crédito USD <?= $this->Number->format($totales_ventas['usd_deuda'],['precision' =>2,'places' =>2])?>

            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modalFactura">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Búsqueda por Documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col">
                            <label>Ingrese Factura</label>
                            <input type="text" name="factura" class="form-control" autocomplete="off" maxlength="13" placeholder="XXXX-XXXXXXXX">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col">
                            <button type="submit" id="btn_factura" class="btn btn-primary"><i class="fa fa-fw fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php

    echo $this->Element('modal_descargar_enviar');
    echo $this->Element('modal_imprimir_venta');

