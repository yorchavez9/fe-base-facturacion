<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Configuracion[]|\Cake\Collection\CollectionInterface $configuraciones
 */
?>
<form>
    <div class="row align-items-end">
        <div class="col-3">
            <label>Variable:</label>
            <input type="text" name="varname" value="<?=$varname?>" class="form-control">
        </div>
        <div class="col-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Consultar</button>
        </div>
    </div>
</form>
<br>
<div class="configuraciones index content">
    <div class="table-responsive">
        <table class="table table-responsive-xl">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('varname') ?></th>
                    <th><?= $this->Paginator->sort('varvalue') ?></th>
                    <th class="actions"><?= __('Acciones') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($configuraciones as $configuracion): ?>
                <tr>
                    <td><?= $this->Number->format($configuracion->id) ?></td>
                    <td><?= h($configuracion->varname) ?></td>
                    <td><?= h($configuracion->varvalue) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Ver'), ['action' => 'view', $configuracion->id]) ?>
                        <?= $this->Html->link(__('Editar'), ['action' => 'edit', $configuracion->id]) ?>
                        <?= $this->Form->postLink(__('Eliminar'), ['action' => 'delete', $configuracion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $configuracion->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('Primero')) ?>
            <?= $this->Paginator->prev('< ' . __('Anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Siguiente') . ' >') ?>
            <?= $this->Paginator->last(__('Último') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Página {{page}} de {{pages}}, mostrando {{current}} registros de {{count}} en total')) ?></p>
    </div>
</div>
