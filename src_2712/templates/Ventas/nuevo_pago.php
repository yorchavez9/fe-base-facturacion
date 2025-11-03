<div class="formato_contenedor">
    
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Registrar Pago para la <?=$venta->documento_tipo?> <?= $venta->documento_numero?></h1>
        </div>
        <div class="col-12 ">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Fecha Pago</th>
                        <th>Monto Pagado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($venta->venta_pagos as $p): ?>
                    <tr>
                        <td><?= $p->nota ?></td>
                        <td><?= $p->fecha_pago->format("Y-m-d") ?></td>
                        <td><?= $this->Number->format($p->monto, ['before' => "S/. "]) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-right">Monto Total de Compra</td>
                        <td><?= $this->Number->format($venta->total, ['before' => "S/. "]) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right">Monto Total Pagado</td>
                        <td><?= $this->Number->format($venta->total_pagos, ['before' => "S/. ", 'places' => 2]) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right">Saldo Pendiente</td>
                        <td><?= $this->Number->format($venta->total_deuda, ['before' => "S/. ", 'places' => 2]) ?></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div class="row">
        <div class="col-6 offset-6">
            <?php
                echo $this->Form->create(null);
                $this->Form->templates(['inputContainer' => '{{content}}'])

            ?>
            <div class="form-group">
                <label class="control-label">Descripción</label>
                <input type="text" name="nota" placeholder="Nota" class="form-control" required="1" />
            </div>
            <div class="form-group">
                <label class="control-label">Monto</label>
                <input type="text" name="monto" placeholder="Monto" class="form-control text-right" value="<?= $this->Number->format($venta->total_deuda, ['places' => 2]) ?>" />
            </div>
            <div class="form-group">
                <label class=" control-label">Fecha</label>
                <input type="text" name="fecha_pago" placeholder="Fecha de Pago" value="<?= date("Y-m-d")?>" class="form-control" />
            </div>
            <div class="form-group">
                <input type="submit" value="Registrar Pago" class="btn btn-primary" />
                <input type="hidden" value="<?= $venta->id ?>" name="venta_id" />
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
