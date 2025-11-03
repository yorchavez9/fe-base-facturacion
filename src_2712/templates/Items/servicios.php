<style>
    .actions{
        width: 110px;
    }

    .actions a{
        display: flex;
    }
</style>
<div class="row">
    <div class="col-12">
        <form class="form">
            <?php
            $this->Form->setTemplates(['inputContainer' => '{{content}}'])
            ?>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg mb-2">
                    <label class="control-label">Productos de Servicio</label>
                    <?= $this->Form->control("opt_categoria_id", ['options' => $opt_categorias,  'value' => $opt_categoria_id, 'empty' => '- Todos -', 'label' => false, 'class' => 'form-control']); ?>
                </div>
                <div class="col-sm-12 col-md-6 col-lg mb-2">
                    <label class="control-label">Buscador General</label>
                    <input placeholder="Código / Nombre / Marca / Categoria" type="text" name="opt_busqueda" class="form-control" value="<?= $opt_busqueda ?>" />
                </div>
                <div class="col-sm-12 col-lg-auto mb-2 d-flex justify-content-center align-items-end">
                    <div>
                        <button type="submit" class="btn btn-primary" >
                            Encontrar
                        </button>
                        &nbsp;
                        <button type="submit" name="exportar" value="1" class="btn btn-outline-primary" >
                            <i class="fas fa-file-excel fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row my-2">
    <div class="col-md-12">
        <table cellpadding="0" cellspacing="0" class="table table-hover">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" class="seleccionar_todo"></th>
                <th scope="col"><?= $this->Paginator->sort('codigo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('categoria_id', ['label' => 'Categoria']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('estado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('precio_venta', ['label' => 'P. Venta']) ?></th>
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($servicios as $producto): ?>
                <tr>
                    <td><input type="checkbox" name="check[]" value="<?= $producto->id ?>" class="seleccion"></td>
                    <td><?= h($producto->codigo) ?></td>
                    <td><?= $producto->Categoria ? $producto->Categoria->nombre : "Sin Categoría" ?></td>
                    <td><?= $producto->nombre ?></td>
                    <td align="center">
                        <?php
                        if ($producto->es_visible == '1'): ?>
                            <a id="item_<?=$producto->id?>" href="#" class="estado_visibilidad" data-id="<?=$producto->id?>" data-visible="0" id="estado<?= $producto->id?>">
                                <i class="fa fa-eye"></i> Visible
                            </a>
                        <?php else: ?>
                            <a id="item_<?=$producto->id?>" href="#" class="estado_visibilidad" data-id="<?=$producto->id?>" data-visible="1" id="estado<?= $producto->id?>">
                                <i class="fa fa-eye-slash"></i> No Visible
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?= $this->Number->format($producto->precio_venta,['places' => 2, 'before' => "S/"]) ?></td>
                    <td class="actions">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton<?= $producto->id ?>" data-toggle="dropdown">Acciones</button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $producto->id ?>">
                                <?php
                                echo $this->Html->link("<span><i class='fas fa-pencil-alt'></i> Editar</span>", ['action' => 'formServicio', $producto->id],['class' => 'dropdown-item',  'escape' => false]);
                                echo $this->Form->postLink("<span><i class='fas fa-trash'></i> Eliminar</span>", ['action' => 'Delete', $producto->id],
                                    ['confirm' => __('¿Seguro que desea eliminar {0}?', $producto->nombre),'class' => 'dropdown-item','escape' => false]);
                                // echo $this->Html->link("<span><i class='fas fa-print'></i> Imprimir</span>", ['action' => 'imprimir', $producto->id],['class' => 'dropdown-item','escape' => false]);
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th scope="col"><input type="checkbox" class="seleccionar_todo"></th>
                <th scope="col"><?= $this->Paginator->sort('codigo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('categoria_id', ['label' => 'Categoria']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('estado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('precio_venta', ['label' => 'P. Venta']) ?></th>

                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
            <tr>
                <td colspan="9">
                    Con los registros seleccionados:
                    <a class="selall" data-accion="eliminar" href="javascript: void(0)" onclick="deleteAll()" ><i class="fa fa-trash"></i> Eliminar</a>
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

<?php echo $this->Element('listado_productos'); ?>

