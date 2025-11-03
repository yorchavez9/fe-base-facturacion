<div class="container">
    <div class="row">
        <div class="col-5">
            <h5>Pagos de la venta <?= "{$venta->documento_serie}-{$venta->documento_correlativo}" ?></h5>
        </div>
        <div class="col">
            <span class="text-deuda"> <i class="fa fa-map-marker fa-fw"></i> Deuda</span>: <?= $this->Number->format($venta->total_deuda, ['places'   =>  2]) ?>
        </div>
        <div class="col">
            <span class="text-pagado"> <i class="fa fa-coins fa-fw"></i> Pagado</span>: <?= $this->Number->format($venta->total_pagos, ['places'    =>  2]) ?>
        </div>
        <div class="col">
            <span class="text-primary"> <i class="fa fa-cash-register fa-fw "></i> Total</span>: <?= $this->Number->format($venta->total, ['places'   =>  2]) ?>
        </div>
    </div>
    <br>
    <?php if (isset($pagos)) : ?>
        <div class="form-row">

            <?php
            $contador = 0;
            foreach ($pagos as $pago) :
                $contador++;
            ?>

                <div class="col-md-4 m">
                    <div class="py-sm-1 py-1">
                    <div class="card"
                        style="
                        border: 1px solid <?= ($pago->estado == 'PAGADO') ? '#07CE59' : '' ?>;
                        color: <?= ($pago->estado == 'PAGADO') ? '#07CE59' : '' ?>;
                        "
                    >
                        <div class="card-body">
                            <div class="">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="pago-title"> Pago #<?= $contador ?> </div>
                                    <?php if($pago->estado == 'PROGRAMADO'):?>
                                        - <div class="font-italic"> PROGRAMADO  </div>
                                        <a class="link-pago" style="max-height: 100% " href="#" onclick="pagarProgramado(<?= $pago->id ?>, <?= $pago->monto ?>)" > <i class="fa fa-cash-register fa-fw "></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="">
                                <p>
                                    <span>Monto</span>: <?= $pago->monto ?>
                                    <br>
                                    <span>Estado</span>: <?= $pago->estado ?>
                                    <br>
                                    <span>Fecha  <?= ($pago->estado == 'PROGRAMADO') ? 'estimada' : '' ?> de Pago</span>: <?= $pago->fecha_pago->format('Y-m-d') ?>
                                    <br>
                                    <span>Descripción</span>: <?= $pago->nota ?>
                                </p>

                            </div>
                        </div>
                    </div>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>

    <?php else : ?>

        <div class="row">
            <div class="col-12">
                <span>
                    <i class="fa fa-exclamation-triangle fa-fw"></i> Sin Pagos Registrados
                </span>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php
echo $this->Element("modal_pagos_ventas",['metodos_pago'    =>  $metodos_pago]);
echo $this->Html->ScriptBlock("var monto_deuda = " . $venta->total_deuda . ";");
echo $this->Html->ScriptBlock("var venta_id = " . $venta->id . ";");
?>
