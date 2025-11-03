<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ItemPrecio $itemPrecio
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Item Precio'), ['action' => 'edit', $itemPrecio->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Item Precio'), ['action' => 'delete', $itemPrecio->id], ['confirm' => __('Are you sure you want to delete # {0}?', $itemPrecio->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Item Precios'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Item Precio'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="itemPrecios view content">
            <h3><?= h($itemPrecio->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Item') ?></th>
                    <td><?= $itemPrecio->has('item') ? $this->Html->link($itemPrecio->item->id, ['controller' => 'Items', 'action' => 'view', $itemPrecio->item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Almacen') ?></th>
                    <td><?= $itemPrecio->has('almacen') ? $this->Html->link($itemPrecio->almacen->nombre, ['controller' => 'Almacenes', 'action' => 'view', $itemPrecio->almacen->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($itemPrecio->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Precio') ?></th>
                    <td><?= $this->Number->format($itemPrecio->precio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($itemPrecio->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($itemPrecio->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descripcion') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($itemPrecio->descripcion)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
