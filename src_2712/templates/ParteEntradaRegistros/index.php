<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteEntradaRegistro[]|\Cake\Collection\CollectionInterface $parteEntradaRegistros
 */
?>
<div class="parteEntradaRegistros index content">
    <?= $this->Html->link(__('New Parte Entrada Registro'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Parte Entrada Registros') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('parte_entrada_id') ?></th>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('item_index') ?></th>
                    <th><?= $this->Paginator->sort('cantidad') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parteEntradaRegistros as $parteEntradaRegistro): ?>
                <tr>
                    <td><?= $this->Number->format($parteEntradaRegistro->id) ?></td>
                    <td><?= $parteEntradaRegistro->has('parte_entrada') ? $this->Html->link($parteEntradaRegistro->parte_entrada->id, ['controller' => 'ParteEntradas', 'action' => 'view', $parteEntradaRegistro->parte_entrada->id]) : '' ?></td>
                    <td><?= $parteEntradaRegistro->has('item') ? $this->Html->link($parteEntradaRegistro->item->id, ['controller' => 'Items', 'action' => 'view', $parteEntradaRegistro->item->id]) : '' ?></td>
                    <td><?= $this->Number->format($parteEntradaRegistro->item_index) ?></td>
                    <td><?= $this->Number->format($parteEntradaRegistro->cantidad) ?></td>
                    <td><?= h($parteEntradaRegistro->created) ?></td>
                    <td><?= h($parteEntradaRegistro->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $parteEntradaRegistro->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $parteEntradaRegistro->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $parteEntradaRegistro->id], ['confirm' => __('Are you sure you want to delete # {0}?', $parteEntradaRegistro->id)]) ?>
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
