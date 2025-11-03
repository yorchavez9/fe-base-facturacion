<?php
/**
 * @var \App\View\AppView $this
 */
?>

<style>
    .actions{
        width: 110px;
    }

    .actions a{
        display: flex;
    }
</style>
<div class="row my-2 mx-0">
    <div class="col-12">
        <form class="form">
            <?php
            $this->Form->setTemplates(['inputContainer' => '{{content}}'])
            ?>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3 mb-3 px-1">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                Categoría
                            </span>
                        </div>
                        <?= $this->Form->control("categoria_id", ['options' => $categorias,  'value' => $cid, 'empty' => '- Todos -', 'label' => false, 'class' => 'form-control form-control-sm']); ?>
                    </div>

                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-3 px-1">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                Marca
                            </span>
                        </div>
                        <?= $this->Form->control("marca_id", ['options' => $marcas, 'value' => $mid, 'empty' => '- Todos -', 'label' => false, 'class' => 'form-control']); ?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-3 px-1">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-search fa-fw"></i>
                            </span>
                        </div>
                        <input placeholder="Código / Nombre / Marca / Categoria" type="text" name="q" class="form-control" value="<?= $q?>" />
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 px-1">
                    <button type="submit" class="btn btn-sm btn-primary" >
                        Encontrar
                    </button>
                    <button type="submit" name="exportar" value="1" class="btn btn-success btn-sm" >
                        Excel
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
<div class="row my-2 mx-0">
    <div class="col-md-12">
        <table cellpadding="0" cellspacing="0" class="table table-responsive" style="min-height: 300px">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" class="seleccionar_todo"></th>
                <th scope="col">Foto</th>
                <th scope="col"><?= $this->Paginator->sort('codigo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('categoria_id', ['label' => 'Categoria']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('marca_id', ['label' => 'Marca']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('unidad') ?></th>
                <th scope="col" class="text-center"><?= $this->Paginator->sort('Estado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('precio_compra', ['label' => 'P. Compra']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('precio_venta', ['label' => 'P. Venta']) ?></th>
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $producto): ?>
                <tr>
                    <td><input type="checkbox" name="check[]" value="<?= $producto->id ?>" class="seleccion"></td>
                    <td><center><?= $this->Html->image("../" . $producto->img_ruta, ['class'=> 'img img-fluid' ,'style' => 'max-height:100px; max-width:100px;']); ?></center></td>
                    <td><?= str_pad($producto->codigo, 7,'0',0) ?></td>
                    <td><?= h($producto->categoria_nombre) ?></td>
                    <td><?= h($producto->marca_nombre) ?></td>
                    <td>
                        <?= h($producto->nombre);?> <br>
                        <small class="ubicacion" ><?= $producto->ubicacion ?></small>
                    </td>
                    <td align="center">
                        <?= h($producto->unidad);?>
                    </td>
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
                    <td class="text-right"><?= $this->Number->format($producto->precio_compra,['places' => 2, 'before' =>  $producto->tipo_moneda === "USD" ? "$" : "S/" ]) ?></td>
                    <td class="text-right"><?= $this->Number->format($producto->precio_venta,['places' => 2, 'before' => $producto->tipo_moneda === "USD" ? "$" : "S/" ]) ?></td>
                    <td class="actions">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Acciones
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li>
                                    <?php echo $this->Html->link("<span><i class='fas fa-pencil-alt'></i> &nbsp; Editar</span>", ['action' => 'Edit', $producto->id],['escape' => false, 'class' => 'dropdown-item']);?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link("<span><i class='fas fa-eye'></i> &nbsp; Ver Kardex</span>", ['action' => 'kardex', $producto->id],['escape' => false, 'class' => 'dropdown-item']);?>
                                </li>
                                <li>
                                    <?php echo $this->Form->postLink("<span><i class='fas fa-trash'></i> &nbsp; Eliminar</span>", ['action' => 'Delete', $producto->id],
                                        ['confirm' => __('¿Está seguro que desea eliminar el registro # {0}?', $producto->id),'escape' => false, 'class' => 'dropdown-item']);?>
                                </li>
                                <li>
                                    <?php
                                    echo $this->Html->link("<span><i class='fas fa-print'></i> &nbsp; Imprimir</span>", ['action' => 'imprimir', $producto->id],['escape' => false, 'class' => 'dropdown-item']);
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th scope="col"><input type="checkbox" class="seleccionar_todo"></th>
                <th scope="col">Foto</th>
                <th scope="col"><?= $this->Paginator->sort('codigo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('categoria_id', ['label' => 'Categoria']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('marca_id', ['label' => 'Marca']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('unidad') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Estado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('precio_compra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('precio_venta', ['label' => 'P. Venta']) ?></th>
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
            <tr>
                <td colspan="9">
                    Con los registros seleccionados:
<!--                    <a href="javascript:void(0)" ><i class="fa fa-edit"></i> Est. Marca</a> |-->
<!--                    <a href="javascript:void(0)" ><i class="fa fa-edit"></i> Est. Categoría</a> |-->
                    <a class="selall" data-accion="visibilidad" data-visible="1" href="#" ><i class="fa fa-eye"></i> Marcar Visible</a> |
                    <a class="selall" data-accion="visibilidad" data-visible="0" href="#" ><i class="fa fa-eye-slash"></i> Marcar Invisible</a> |
                    <a class="selall" data-accion="eliminar" href="#" ><i class="fa fa-trash"></i> Eliminar</a>
                </td>
            </tr>
            </tfoot>
        </table>
        <?= $this->element("paginador")?>
        <div class="alert alert-info text-center" style="display: none">
            Valor aproximado del inventario en <?= number_format((float)$totales, 2) ?>
        </div>
    </div>
</div>

<div class="modal fade"
     id="setCategoriaModal"
     tabindex="-1" role="dialog"
     aria-labelledby="setCategoriaLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setCategoriaLabel">Seleccione categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <?= $this->Form->control('categoria_id', ['class' => 'form-control form-control-sm']) ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <a class="selall btn btn-primary" data-accion="set-categoria" href="javascript:void(0)" ><i class="fa fa-save"></i> Guardar</a> |
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


