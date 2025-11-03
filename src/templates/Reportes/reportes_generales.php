<div id="basic-waypoint">
    <section>
        <div class="container">
            <form method="GET">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-xl-2 pb-2">
                        <input type="text" readonly name="fecha_ini" class="form-control form-control-sm" value="<?= $fecha_ini ?>">
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-2 pb-2">
                        <input type="text" readonly name="fecha_fin" class="form-control form-control-sm" value="<?= $fecha_fin ?>">
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-2 pb-2">
                        <?=
                        $this->Form->control(
                            'moneda_codigo',
                            [
                                'class' =>  'form-control form-control-sm',
                                'id'    =>  'moneda_codigo',
                                'label' =>  false,
                                'value' =>  $moneda_codigo,
                                'options'   =>  ['PEN'  =>  'Soles',    'USD'   =>  'Dólares']
                            ]
                        )
                        ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-2 text-center pb-2">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-search fa-fw"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="container-fluid bg-blue mt-2">
        <div class="container">
            <div class="form-row">
                <div class="col-md-3 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Ingresos <span class="counter timer count-title count-number" data-prepend-moneda="<?= $moneda_simbolo ?>" data-from="0" data-to="<?= $ingresos["TOTAL_G"] ?>" data-speed="2500"></span></h6>
                            <div class="row">
                                <div class="col">
                                    <span>Ventas</span>
                                </div>
                                <div class="col">
                                    <span class="counter timer count-title count-number" data-prepend-moneda="<?= $moneda_simbolo ?>" data-from="0" data-to="<?= $ingresos["TOTAL_NV"] ?>" data-speed="2500"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <span>Impuestos (Ventas)</span>
                                </div>
                                <div class="col">
                                    <span class="counter timer count-title count-number" data-prepend-moneda="<?= $moneda_simbolo ?>" data-from="0" data-to="<?= $ventas_impuestos["IMPUESTOS_G"] ?>" data-speed="2500"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row text-center">
                        <?php foreach ($metodos_pago as $mtd_pago) : ?>
                            <div class="col-sm-3 col-md-3 p-3">
                                <?= $mtd_pago ?>: <br>
                                <?= $this->Number->format($pagos_por_metodo_pago["{$mtd_pago}"], ['places'    =>  2, 'before'    =>  $moneda_simbolo]) ?>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row text-center">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <span>POR COBRAR</span>: <br>
                            <?= $this->Number->format($deudas_por_cobrar["TOTAL"], ['places'  =>    2, 'before' =>  $moneda_simbolo]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <canvas id="chartHistoricoVentasDiarios"></canvas>
                </div>
            </div>

        </div>
    </section>
</div>



<?php

echo $this->Html->ScriptBlock("var _ventas = " . json_encode($ventas) . ";");
echo $this->Html->ScriptBlock("var _fe_plan = 0;");
?>
