<form class="form">
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-3">
            <div class="form-group mb-2">
                <label class="control-label">Vendedor</label>
                <?= $this->Form->control("uid", ['options' => $usuarios, 'empty' => '- Todos -', 'label' => false, 'class' => 'form-control form-control-sm', 'value' => $uid])?>
            </div>
        </div>
        <div class="col-sm-12 col-md col-lg-2">
            <div class="form-group mb-2">
                <label class="control-label">F. Inicio</label>
                <input class="form-control form-control-sm text-center fecha" name="fecha_ini" value="<?= $fini ?>">
            </div>
        </div>
        <div class="col-sm-12 col-md col-lg-2">
            <div class="form-group mb-2">
                <label class="control-label">F. Fin</label>
                <input class="form-control form-control-sm text-center fecha" name="fecha_fin" value="<?= $ffin ?>">
            </div>
        </div>
        <div class="col-sm-4  col-md-auto col-lg-2 d-flex align-items-end justify-content-center">
            <div class="form-group mb-2">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search fa-fw"></i></button>
                <button type="submit" name="exportar" value="excel" class="btn btn-primary btn-sm"><i class="fas fa-file-excel fa-fw"></i></button>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col">
        <?= $this->Flash->Render() ?>
        <table class="table table-responsive-xl">
            <thead>
                <tr>
                    <th><?=$this->Paginator->sort('Ventas.fecha_venta', ['label'=>'Fecha'])?></th>
                    <th><?=$this->Paginator->sort('Ventas.cliente_razon_social', ['label'=>'Cliente'])?></th>
                    <th><?=$this->Paginator->sort('item_codigo', ['label'=>'Código'])?></th>
                    <th><?=$this->Paginator->sort('item_nombre', ['label'=>'Item'])?></th>
                    <th><?=$this->Paginator->sort('cantidad', ['label'=>'Cantidad.'])?></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventaregistros as $v): ?>
                <tr>
                    <td><?= $v->Venta->fecha_venta->format("Y-m-d")?></td>
                    <td><?= $v->Venta->cliente_razon_social ?></td>
                    <td><?= $v->item_codigo ?></td>
                    <td><?= $v->item_nombre ?></td>
                    <td><?= $v->cantidad?></td>
                    <td>
                    <?php
                        echo $this->Html->Link("<i class='fa fa-search fa-fw'></i> Ver Venta", ['controller' => 'Ventas',  'action' => 'detalles', $v->venta_id], ['escape' => FALSE]);
                    ?>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <div class="paginator text-center">
            <ul class="pagination">
                <?= $this->Paginator->prev(' « Anterior ') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(' Siguiente » ') ?>
            </ul>
            <p><?= $this->Paginator->counter('Página {{page}} de {{pages}}, mostrando {{current}} registros de {{count}} en total, comenzando en {{start}}, terminando en {{end}}') ?></p>
            <p class="alert alert-info">
                Total de Productos vendidos en la Actual Consulta:<b> <?= $total_items ?> Items</b>
            </p>
        </div>
    </div>
</div>
