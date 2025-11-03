<div class="row">
    <div class="col">
        <table class="table table-sm table-bordered ">

            <tr>
                <td width="200px">RAZÓN SOCIAL</td>
                <td colspan="5"><b><?= $cliente->razon_social ?></b></td>
            </tr>
            <tr>
                <td>NOMBRE COMERCIAL</td>
                <td colspan="5"><?= $cliente->nombre_comercial ?></td>
            </tr>
            <tr>
                <td>DOMICILIO FISCAL</td>
                <td colspan="5"><?= $cliente->domicilio_fiscal ?></td>
            </tr>
            <tr>
                <td style="width: 25%;">RUC</td>
                <td style="width: 25%;" colspan="2"><?= $cliente->ruc ?></td>
                <td style="width: 25%;">UBIGEO</td>
                <td style="width: 25%;" colspan="2"><?= $cliente->ubigeo_dpr ?></td>
            </tr>
            <tr>
                <td>F. CREACIÓN</td>
                <td colspan="2"><?= $cliente->created ? $cliente->created->format('Y/m/d h:m:s A') : "" ?></td>
                <td width="150px">ESTADO</td>
                <td colspan="2"><?= $cliente->estado ?></td>
            </tr>
            <tr>
                <td>TELEFONO</td>
                <td colspan="2"><?= $cliente->telefono ?></td>
                <td width="150px">CELULAR</td>
                <td colspan="2"><?= $cliente->celular ?></td>
            </tr>
            <tr>
                <td>CORREO</td>
                <td colspan="4"><?= $cliente->correo ?></td>
            </tr>
        </table>
    </div>
</div>

<form class="form">
    <?php
    $this->Form->setTemplates(['inputContainer' => '{{content}}'])
    ?>
    <div class="row mb-3">
        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label class="control-label">Rango de Fechas</label>
                <div class="form-group row">
                    <div class="col-6">
                        <input class="form-control form-control-sm text-center fecha" name="fecha_ini" value="<?= $fini ?>">
                    </div>
                    <div class="col-6">
                        <input class="form-control form-control-sm text-center fecha" name="fecha_fin" value="<?= $ffin ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <label>&nbsp;</label><br>
            <a class="btn btn-outline-primary btn-sm w-100" href="javascript:abrirModalTiposDoc()">Tipos de Doc</a>
        </div>
        <div class="col-sm-12 col-md-4">
            <label class="control-label">&nbsp;</label><br />
            <div class="input-group">
                <button type="submit" class="btn btn-sm btn-primary mx-1">
                    <i class="fa fa-fw fa-search"></i> Buscar
                </button>
                <div class="input-group-append">
                    <button type="submit" name="exportar" value="1" class="btn btn-sm btn-primary mr-1">
                        <i class="fa fa-fw fa-file-excel"></i> Exportar
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
                                <input type="checkbox" name="BOLETA" value="BOLETA" class="form-check-input" id="exampleCheck1" <?= $opt_boleta == "BOLETA" ? "checked" : "" ?>>
                                <label class="form-check-label" for="exampleCheck1">BOLETAS</label>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="FACTURA" value="FACTURA" class="form-check-input" id="exampleCheck2" <?= $opt_factura == "FACTURA" ? "checked" : "" ?>>
                                <label class="form-check-label" for="exampleCheck2">FACTURAS</label>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="NOTAVENTA" value="NOTAVENTA" class="form-check-input" id="exampleCheck3" <?= $opt_notaventa == "NOTAVENTA" ? "checked" : "" ?>>
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
<div class="row">
    <div class="col-md-12 table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 150px;"><?= $this->Paginator->sort('fecha_venta', ['label' => 'Fecha / Hora']) ?></th>
                    <th><?= $this->Paginator->sort('cliente_id', ['label' => 'Cliente']) ?></th>
                    <th style="width: 150px;"><?= $this->Paginator->sort('documento_tipo', ['label' => 'Documento']) ?></th>
                    <th style="width: 200px;" colspan="2">Totales</th>
                    <th>Enviar a Sunat</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $v) : ?>
                    <tr class="<?= ($v->total_deuda > 0) ? "warning" : "" ?>" style="color: <?= $v->estado == "ANULADO" ? "#f25a5a" : ($v->estado == "PORANULAR" ? "#ff9500" : "") ?>">
                        <td>
                            <i class="fas fa-calendar-alt fa-fw"></i> <?= $v->fecha_venta->format("d/m/Y") ?><br />
                            <i class="fas fa-clock fa-fw"></i> <?= $v->fecha_venta->format("H:i:s") ?>
                        </td>
                        <td>
                            DNI/RUC: <?= $v->cliente_doc_numero ?><br />
                            <?= $v->cliente_razon_social ?>
                        </td>
                        <td>
                            <?= $opt_documento_tipos[$v->documento_tipo] ?? "" ?><br />
                            <?= $this->Html->Link("{$v->documento_serie}-{$v->documento_correlativo}", ['action' => 'detalles', $v->id]) ?>
                        </td>
                        <td>
                            Subtotal<br />
                            IGV<br />
                            Total
                        </td>

                        <td class="text-right" style="width: 180px;">
                            <?= $this->Number->format($v->subtotal, ['places' => 2]) ?> <?= $v->tipo_moneda ?><br />
                            <?= $this->Number->format($v->igv_monto, ['places' => 2]) ?> <?= $v->tipo_moneda ?><br />
                            <?= $this->Number->format($v->total, ['places' => 2]) ?> <?= $v->tipo_moneda ?>
                        </td>
                        <td id="row_<?= $v->id ?>">
                            <?php if ($v->estado == "ANULADO") : ?>
                                <i>COMPROBANTE ANULADO</i>
                            <?php elseif ($v->estado == "PORANULAR") : ?>
                                <i>COMPROBANTE POR ANULAR</i>
                            <?php else : ?>
                                <?php if ($v->documento_tipo == 'BOLETA') : ?>
                                    <?php if ($v->estado_sunat == "NOENVIADO") { ?>
                                        <i>Envío Automático Programado en Resumen Diario</i>
                                    <?php } else { ?>
                                        <i><?= $v->estado_sunat ?></i>
                                    <?php } ?>
                                <?php elseif ($v->documento_tipo == 'FACTURA') : ?>
                                    <?php if ($v->estado_sunat != 'ACEPTADO') : ?>
                                        <a href="javascript:void(0)" data-factura-id="<?= $v->factura_id ?>" onclick="MisVentas.enviarFacturaSunat(this)">
                                            <i class="fas fa-upload fa-fw"></i> Enviar a Sunat
                                        </a>
                                    <?php else : ?>
                                        Comprobante Aceptado
                                    <?php endif; ?>
                                <?php elseif ($v->documento_tipo == 'NOTAVENTA') : ?>
                                    No aplica a Nota de Venta
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Acciones
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                    <?php if ($whatsapp_active == "1") { ?>
                                        <a class="dropdown-item" href='javascript:ModalDescargarEnviar.Abrir("<?= $v->documento_tipo ?>",<?= $v->id ?>)'>
                                            <i class="fas fa-paper-plane fa-fw"></i> Enviar por
                                            <i class="fab fa-whatsapp fa-fw"></i> ó <i class="fas fa-envelope fa-fw"></i>
                                        </a>
                                    <?php } ?>


                                    <?php
                                    echo $this->Html->Link("<i class='fa fa-search fa-fw'></i> Detalles", ['controller' => 'Ventas', 'action' => 'detalles', $v->id], ['class' => 'dropdown-item', 'escape' => FALSE]);

                                    if (!in_array($v->documento_tipo, ['BOLETA', 'FACTURA'])) {
                                        echo $this->Html->Link("<i class='fas fa-print fa-fw'></i> Imprimir A4", ['controller' => 'Ventas', 'action' => 'descargarNotaVentaPdf', $v->id, 'a4', '0'], ['class' => 'dropdown-item', 'escape' => FALSE, 'target' => '_blank']);
                                        echo $this->Html->Link("<i class='fas fa-print fa-fw'></i> Imprimir Ticket", ['controller' => 'Ventas', 'action' => 'descargarNotaVentaTicket', $v->id, 'ticket', '0'], ['class' => 'dropdown-item', 'escape' => FALSE, 'target' => '_blank']);
                                    }

                                    ?>


                                    <?php

                                    // echo "<a class='dropdown-item' href='javascript: return false;' onclick='MisVentas.enviarCorreoCpe({$v->id})'><i class='fa fa-envelope fa-fw'></i> Enviar por Correo</a>";

                                    if ($v->documento_tipo == '01') {
                                        echo "<a class='dropdown-item' href='javascript: void(0);' onclick='MisVentas.verificarCpe({$v->id})'><i class='fa fa-check fa-fw'></i> Verificar Factura</a>";
                                    }

                                    if ($v->total_deuda > 0) {
                                        echo $this->Html->Link("<i class='fas fa-money-bill fa-fw'></i> Pagar", ['action' => 'nuevoPago', $v->id], ['class' => 'dropdown-item', 'escape' => FALSE]);
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="paginator text-center">
            <ul class="pagination">
                <?= $this->Paginator->first(' «« Primero ') ?>
                <?= $this->Paginator->prev(' « Anterior ') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(' Siguiente » ') ?>
                <?= $this->Paginator->last(' Último »» ') ?>
            </ul>
            <p><?= $this->Paginator->counter('Página {{page}} de {{pages}}, mostrando {{current}} registros de {{count}} en total, comenzando en {{start}}, terminando en {{end}}') ?></p>

            <div class="alert alert-info text-center">
                Total en Ventas S/<?= $totales->sum_total ?>. Total pagado S/<?= $totales->sum_pagos ?>. Total en Crédito S/<?= $totales->sum_deuda ?>
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

                    <br />
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope fa-fw"></i> </span>
                        </div>
                        <input type="text" name="correo" class="form-control" />
                    </div>
                    <input type="hidden" name="venta_id" class="form-control" />
                    <input type="hidden" name="cliente_id" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="" onclick="MisVentas.enviarCorreoCpeBtn()" class="btn btn-primary"><i class="fa fa-plane fa-fw"></i> Enviar</button>
            </div>
        </div>
    </div>
</div>
<!----->

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



<?php echo
$this->Element('modal_descargar_enviar');
?>