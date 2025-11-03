<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ItemPrecio[]|\Cake\Collection\CollectionInterface $itemPrecios
 */
?>
<div class="itemPrecios index content">
    <?= $this->Html->link(__('New Item Precio'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Item Precios') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('precio') ?></th>
                    <th><?= $this->Paginator->sort('item_id') ?></th>
                    <th><?= $this->Paginator->sort('almacen_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itemPrecios as $itemPrecio): ?>
                <tr>
                    <td><?= $this->Number->format($itemPrecio->id) ?></td>
                    <td><?= $this->Number->format($itemPrecio->precio) ?></td>
                    <td><?= $itemPrecio->has('item') ? $this->Html->link($itemPrecio->item->id, ['controller' => 'Items', 'action' => 'view', $itemPrecio->item->id]) : '' ?></td>
                    <td><?= $itemPrecio->has('almacen') ? $this->Html->link($itemPrecio->almacen->nombre, ['controller' => 'Almacenes', 'action' => 'view', $itemPrecio->almacen->id]) : '' ?></td>
                    <td><?= h($itemPrecio->created) ?></td>
                    <td><?= h($itemPrecio->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $itemPrecio->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $itemPrecio->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $itemPrecio->id], ['confirm' => __('Are you sure you want to delete # {0}?', $itemPrecio->id)]) ?>
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
