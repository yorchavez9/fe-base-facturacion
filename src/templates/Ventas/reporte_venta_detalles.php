
<p>
    Opciones :
    <?php echo $this->Html->link("<i class='fa fa-file-pdf fa-fw'></i> Imprimir A4",['controller' => 'SunatFeFacturas', 'action' => 'descargarPdf', $venta->id, 'a4', '0'],['escape'=>false, 'target' =>'_blank'] )?> |
    <?php echo $this->Html->link("<i class='fa fa-envelope fa-fw'></i> Imprimir Ticket",['controller' => 'SunatFeFacturas', 'action' => 'descargarPdf', $venta->id, 'ticket', '0'],['escape'=>false, 'target' =>'_blank'] )?>
</p>
<hr/>
<div class="row">
    <div class="col-md-12">
        <fieldset>
            <h6>Información del Cliente</h6>
            <table class="table table-bordered">
                <tr>
                    <th>Emisor</th>
                    <td><?= $emisor->ruc ?>: <?= $emisor->razon_social?></td>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <td>
                        <?= $venta->cliente_doc_numero ?>: <?= $venta->cliente_razon_social ?>
                    </td>
                </tr>
                <tr>
                    <th>Serie Correlativo</th>
                    <td><?= $venta->documento_serie ?>-<?= $venta->documento_correlativo?></td>
                </tr>
                <tr>
                    <th>Fecha de Venta</th>
                    <td><?= $venta->fecha_venta->format("Y-m-d")?></td>
                </tr>
                <tr>
                    <th>Monto Subtotal</th>
                    <td>
                        <?= $venta->tipo_moneda ?>
                        <?= $this->Number->Format($venta->subtotal, ['places' => 2]) ?>
                    </td>
                </tr>
                <tr>
                    <th>Monto IGV</th>
                    <td>
                        <?= $venta->tipo_moneda ?>
                        <?= $this->Number->Format($venta->igv_monto, ['places' => 2]) ?>
                    </td>
                </tr>
                <tr>
                    <th>Monto Total</th>
                    <td>
                        <?= $venta->tipo_moneda ?>
                        <?= $this->Number->Format($venta->total, ['places' => 2]) ?>
                    </td>
                </tr>

            </table>
        </fieldset>
        <fieldset>
            <h6>Items de la <?= $venta->documento_tipo?></h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 150px">Código</th>
                            <th>Descripción</th>
                            <th style="width: 150px">Cantidad</th>
                            <th colspan="2" >P. Unitario</th>
                            <th colspan="2" >P. Total</th>
                            <?php if ($usuario_sesion['rol'] == 'SUPERADMIN') : ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($venta->venta_registros as $i): ?>
                        <tr>
                            <td><?= str_pad( $i->item_index, 7, "0", 0); ?></td>
                            <td><?= $i->item_codigo; ?></td>
                            <td>
                                <?= $i->item_nombre ?>
                            </td>
                            <td><?= $i->cantidad ?> <?= $i->item_unidad?> </td>
                            <td style="width: 50px;"><?= $venta->tipo_moneda ?></td>
                            <td style="width: 100px;" class="text-right"><?= $this->Number->format($i->precio_uventa, ['places' =>2]) ?> </td>
                            <td style="width: 50px;"><?= $venta->tipo_moneda ?></td>
                            <td style="width: 100px;" class="text-right"><?= $this->Number->format($i->precio_total, ['places' => 2]) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
        <fieldset>
            <h6>Comentarios Adicionales</h6>
            <p><?= ($venta->comentarios != '') ? $venta->comentarios : " Sin Comentarios " ?></p>
        </fieldset>
    </div>
</div>
