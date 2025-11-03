<form class="form">
    <?php
    $this->Form->setTemplates(['inputContainer' => '{{content}}'])
    ?>
    <div class="row row-cols-sm-3 px-3">
        <div class="col-sm-12 col-lg-5 mb-2 px-1">
            <div class="input-group input-group-sm d-flex flex-nowrap">
                <div class="input-group-prepend">
                    <span class="input-group-text">Fechas</span>
                </div>
                <input class="form-control-sm text-center fecha" name="fecha_ini" value="<?= $fini ?>">
                <input class="form-control-sm text-center fecha" name="fecha_fin" value="<?= $ffin ?>">
            </div>
        </div>
        <div class="col-sm-5 mb-2 px-1">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">Nombre</span>
                </div>
                <input type="text" name="opt_nombres" value="<?=$opt_nombres?>" class="form-control " placeholder="Nombre" autocomplete="off">
            </div>
        </div>
        <div class="col mb-2 px-1">
            <a class="btn btn-sm btn-outline-primary w-100" href="javascript:abrirModalUsuarios()">Usuarios</a>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="modalUsuarios">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="pb-2">
                        <?= $this->Form->Control("opt_usuario", [
                            'label' => false,
                            'options' => $usuarios,
                            'empty' => '- Todos -',
                            'value' => $opt_usuario,
                            'class' => 'form-control form-control-sm'
                        ]) ?>
                    </div>
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
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" style="min-height: 45vh">
            <table class="table table-sm table-striped" >
                <thead>
                <tr>
                    <th style="width: 150px;">
                        <?=$this->Paginator->sort('fecha_venta', ['label'=>'Fecha / Hora / ID'])?>
                    </th>
                    <th><?=$this->Paginator->sort('cliente_id', ['label'=>'Cliente'])?></th>
                    <th style="width: 150px;"><?=$this->Paginator->sort('documento_tipo', ['label'=>'Documento'])?></th>
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




<div class="modal" tabindex="-1" role="dialog" id="modalEnviarCorreo">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Enviar CPE por Correo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="width: 500px;">

                <div class="col-md-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-user fa-fw"></i> </span>
                        </div>
                        <input type="text" readonly class="form-control" name="razon_social">
                    </div>

                    <br/>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" ><i class="fas fa-envelope fa-fw"></i> </span>
                        </div>
                        <input type="text" name="correo" class="form-control"/>
                    </div>
                    <input type="hidden" name="venta_id" class="form-control"/>
                    <input type="hidden" name="cliente_id" class="form-control"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" onclick="MisVentas.enviarCorreoCpeBtn()" class="btn btn-primary"><i class="fa fa-plane fa-fw"></i> Enviar</button>
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



<?php   echo $this->Element('modal_descargar_enviar');
        echo $this->Element('modal_imprimir_venta');
        echo $this->Element('modal_anular_factura');
        echo $this->Element('modal_anular_comprobante');
        echo $this->Element('modal_anular_boleta');
        echo $this->Element('modal_modificar_comprobante');
        echo $this->Element('venta_detalles');
        echo $this->Element('modal_sunat_crd');
