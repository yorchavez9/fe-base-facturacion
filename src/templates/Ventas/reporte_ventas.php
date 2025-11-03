<form class="form">
    <?php
    $this->Form->setTemplates(['inputContainer' => '{{content}}'])
    ?>
    <div class="row">
        <div class="col-12 col-sm">
            <div class="form-group">
                <label class="control-label">Periodo Tributario</label>
                <div class="form-group d-flex">
                    <div>
                        <input class="form-control form-control-sm text-center" name="periodo" value="<?= $periodo ?>" >
                    </div>
                    <div class="">
                        <button class="btn btn-outline-primary btn-sm btn-search" type="submit" id="">  <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3" style="max-width:160px">
            <label class="control-label">&nbsp;</label><br/>
            <div class="input-group">
                <button type="submit" name="exportar" value="1" class="btn btn-sm btn-outline-primary mx-1 w-100">
                    <i class="fa fa-fw fa-file-excel"></i> Exportar
                </button>
            </div>
        </div>
        <div class="col-12 col-sm-3" style="max-width:160px">
            <label class="control-label">&nbsp;</label><br/>
            <div class="input-group">
                <button type="submit" name="exportar_completo" value="1" class="btn btn-sm btn-outline-primary mx-1 w-100">
                    <i class="fa fa-fw fa-file-excel"></i> Exportar2
                </button>
            </div>
        </div>
    </div>

</form>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive gw-table" >
            <table class="table table-striped table-hover table-sm " >
            <thead>
            <tr>
                <th style="width: 150px;"><?=$this->Paginator->sort('fecha_venta', ['label'=>'Fecha / Hora'])?></th>
                <th><?=$this->Paginator->sort('cliente_id', ['label'=>'Cliente'])?></th>
                <th style="width: 150px;"><?=$this->Paginator->sort('documento_tipo', ['label'=>'Documento'])?></th>
                <th style="width: 200px;" colspan="2">Totales</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($ventas as $v): ?>
                    <tr class="<?= ($v->total_deuda > 0) ? "warning":""?>" style="color: <?= $v->estado == "ANULADO" ? "#f25a5a" : ($v->estado == "PORANULAR" ? "#ff9500" : "") ?>" >
                    <td>
                        <i class="fas fa-calendar-alt fa-fw"></i> <?= $v->fecha_venta->format("d/m/Y")?><br/>
                        <i class="fas fa-clock fa-fw"></i> <?= $v->fecha_venta->format("H:i:s")?>
                    </td>
                    <td>
                        DNI/RUC: <?= $v->cliente_doc_numero?><br/>
                        <?= $v->cliente_razon_social?> <br>
                        <?php if(isset($v->sunat_fe_notas) && count($v->sunat_fe_notas) > 0):
                            foreach ($v->sunat_fe_notas as $nota) :
                                echo "{$nota->serie}-{$nota->correlativo} <br> "  ;
                            endforeach;
                        endif; ?>
                    </td>
                    <td>
                        <?= $opt_documento_tipos[$v->documento_tipo] ?? "" ?><br/>
                        <?= "{$v->documento_serie}-{$v->documento_correlativo}" ?>
                    </td>
                    <td>
                        Subtotal<br/>
                        IGV<br/>
                        Total
                    </td>
                    <td class="text-right" style="width: 180px;">
                        <?= $this->Number->format($v->subtotal, [ 'places' => 2,'precision'=>2])?> <?= $v->tipo_moneda?><br/>
                        <?= $this->Number->format($v->igv_monto, [ 'places' => 2,'precision'=>2])?> <?= $v->tipo_moneda?><br/>
                        <?= $this->Number->format($v->total, [ 'places' => 2,'precision'=>2])?> <?= $v->tipo_moneda?>
                    </td>
                    <td id="row_<?= $v->id ?>">
                        <?php if ($v->estado == "ANULADO") : ?>
                            <i>COMPROBANTE ANULADO</i><br>
                            MOTIVO: <i><?= $v->motivo_anulacion ?></i><br>
                        <?php elseif ($v->estado == "PORANULAR") : ?>
                            <i>COMPROBANTE POR ANULAR</i>
                        <?php else: ?>
                            <?php if ($v->documento_tipo == 'BOLETA') : ?>
                                <?php if ($v->estado_sunat == "NOENVIADO") { ?>
                                    <i>Envío Automático Programado en Resumen Diario</i>
                                <?php } else  { ?>
                                    <i><?= $v->estado_sunat ?></i>
                                <?php } ?>
                            <?php elseif ($v->documento_tipo == 'FACTURA'): ?>
                                <?php if ($v->estado_sunat != 'ACEPTADO'): ?>
                                    <!--<a href="javascript:void(0)" data-factura-id="<?= $v->factura_id?>" onclick="FilaVentas.enviarFacturaSunat(this)">
                                        <i class="fas fa-upload fa-fw"></i> Enviar a Sunat
                                    </a>-->
                                    <?= $v->estado_sunat == 'NOENVIADO' ? 'No enviado' : $v->estado_sunat ?>
                                <?php else: ?>
                                    Comprobante Aceptado
                                <?php endif; ?>
                            <?php elseif($v->documento_tipo == 'NOTAVENTA'):?>
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
                                <?php echo $this->Html->Link("<i class='fa fa-search fa-fw'></i> Detalles", ['action' => 'reporte-venta-detalles', $v->id], ['class' =>'dropdown-item', 'escape' => FALSE]); ?>
                            </div>

                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div class="paginator text-center">
            <?= $this->element("paginador"); ?>
            <div class="alert alert-info text-center">
                <div>
                    Totales en Soles (PEN) : <?= $totales['pen_total'] ?>
                </div>
                <div class="d-flex">
                    <div class="col"> Op. Gravadas (PEN)        <br> <?= round($totales['pen_op_gravadas'] ,2)?>  </div>
                    <div class="col"> Op. Gratuitas (PEN)       <br> <?= round($totales['pen_op_gratuitas'],2) ?>  </div>
                    <div class="col"> Op. Exoneradas (PEN)      <br> <?= round($totales['pen_op_exoneradas'],2) ?>  </div>
                    <div class="col"> Op. Inafectadas (PEN)     <br> <?= round($totales['pen_op_inafectadas'],2) ?>  </div>
                </div>
                <div>
                    Total en Dólares (USD) : <?= $totales['usd_total'] ?>
                </div>
                <div class="d-flex">
                    <div class="col"> Op. Gravadas (USD)        <br> <?= round($totales['usd_op_gravadas'],2) ?> </div>
                    <div class="col"> Op. Gratuitas (USD)       <br> <?= round($totales['usd_op_gratuitas'],2) ?> </div>
                    <div class="col"> Op. Exoneradas (USD)      <br> <?= round($totales['usd_op_exoneradas'],2) ?> </div>
                    <div class="col"> Op. Inafectadas (USD)     <br> <?= round($totales['usd_op_inafectadas'],2) ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
