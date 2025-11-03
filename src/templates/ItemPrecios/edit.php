<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ItemPrecio $itemPrecio
 * @var string[]|\Cake\Collection\CollectionInterface $items
 * @var string[]|\Cake\Collection\CollectionInterface $almacenes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $itemPrecio->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $itemPrecio->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Item Precios'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="itemPrecios form content">
            <?= $this->Form->create($itemPrecio) ?>
            <fieldset>
                <legend><?= __('Edit Item Precio') ?></legend>
                <?php
                    echo $this->Form->control('precio');
                    echo $this->Form->control('descripcion');
                    echo $this->Form->control('item_id', ['options' => $items, 'empty' => true]);
                    echo $this->Form->control('almacen_id', ['options' => $almacenes, 'empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
