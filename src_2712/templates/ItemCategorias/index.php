
<form class="form">

    <div class="input-group mb-3">
        <input type="search" class="form-control" name="opt_nombre" placeholder="Filtro" value="<?= $opt_nombre?>"/>
        <div class="input-group-append">
            <input type="submit" value="Buscar" class="btn btn-primary"/>
        </div>
    </div>

</form>
<div class="row">
    <div class="col-md-12 table-responsive">
        <table class="table table-striped" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" class="seleccionar_todo"> Código</th>
                    <th><?= $this->Paginator->sort('orden') ?></th>
                    <th><?= $this->Paginator->sort('nombre') ?></th>
                    <th><?= $this->Paginator->sort('descripcion') ?></th>
                    <!-- th><?= $this->Paginator->sort('nro_items',['label' => 'Items']) ?></th -->
                    <th class="actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $item): ?>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="check[]" value="<?= $item->id ?>" class="seleccion">
                            <?= str_pad($item->id, 7,0,0)?>
                        </label>
                    </td>
                    <td><input type="text" data-id="<?= $item->id?>" value="<?= h($item->orden);?>" class="selorder form-control" style="width: 80px;" /></td>
                    <td><?= h($item->nombre);?></td>
                    <td><?= h($item->descripcion) ?></td>
                    <!-- td><?= $this->Html->Link($item->nro_items . " Items", ['action' => 'index', $item->id]) ?></td -->
                    <td class="actions" style="min-width: 100px;">
                        <?php
                            echo $this->Html->link("<i class='fa fa-edit'></i> Editar", "#", ['data-id' => $item->id, 'class' => 'link_editar', 'escape' => false]);
                            echo " | ";
                            echo $this->Form->postLink("<i class='fa fa-trash'></i> Eliminar", ['action' => 'delete', $item->id], ['confirm' => __('¿Seguro que quieres eliminar {0}?', $item->nombre), 'escape' => false])
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="10">
                        Con los registros seleccionados:
                        <a class="selall" href="javascript: void(0);" onclick="orderAll()" ><i class="fas fa-arrows-alt-v fa-fw"></i>Ordenar</a> |
                        <a class="selall" data-accion="eliminar" href="javascript: void(0)" onclick="deleteAll()" ><i class="fa fa-trash fa-fw"></i>Eliminar</a>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first(' «« Primero ') ?>
                <?= $this->Paginator->prev(' « Anterior ') ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(' Siguiente » ') ?>
                <?= $this->Paginator->last(' Último »» ') ?>
            </ul>
            <p><?= $this->Paginator->counter(__('Pagina {{page}} de {{pages}}, mostrando {{current}} item(s) de {{count}} total')) ?></p>
        </div>
    </div>
</div>
<?php
    echo $this->element("global_modal_categoria");
    // echo $this->Html->scriptBlock(" var _csrfToken = '" . $this->request->param('_csrfToken') . "';" );
?>
