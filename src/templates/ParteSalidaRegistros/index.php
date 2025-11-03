<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteSalidaRegistro[]|\Cake\Collection\CollectionInterface $parteSalidaRegistros
 */
?>
<div class="parteSalidaRegistros index content">
    <?= $this->Html->link(__('New Parte Salida Registro'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Parte Salida Registros') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('parte_salida_id') ?></th>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('item_index') ?></th>
                    <th><?= $this->Paginator->sort('cantidad') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parteSalidaRegistros as $parteSalidaRegistro): ?>
                <tr>
                    <td><?= $this->Number->format($parteSalidaRegistro->id) ?></td>
                    <td><?= $parteSalidaRegistro->has('parte_salida') ? $this->Html->link($parteSalidaRegistro->parte_salida->id, ['controller' => 'ParteSalidas', 'action' => 'view', $parteSalidaRegistro->parte_salida->id]) : '' ?></td>
                    <td><?= $parteSalidaRegistro->has('item') ? $this->Html->link($parteSalidaRegistro->item->id, ['controller' => 'Items', 'action' => 'view', $parteSalidaRegistro->item->id]) : '' ?></td>
                    <td><?= $this->Number->format($parteSalidaRegistro->item_index) ?></td>
                    <td><?= $this->Number->format($parteSalidaRegistro->cantidad) ?></td>
                    <td><?= h($parteSalidaRegistro->created) ?></td>
                    <td><?= h($parteSalidaRegistro->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $parteSalidaRegistro->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $parteSalidaRegistro->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $parteSalidaRegistro->id], ['confirm' => __('Are you sure you want to delete # {0}?', $parteSalidaRegistro->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
