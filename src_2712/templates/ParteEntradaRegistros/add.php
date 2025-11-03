<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteEntradaRegistro $parteEntradaRegistro
 * @var \Cake\Collection\CollectionInterface|string[] $parteEntradas
 * @var \Cake\Collection\CollectionInterface|string[] $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Parte Entrada Registros'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteEntradaRegistros form content">
            <?= $this->Form->create($parteEntradaRegistro) ?>
            <fieldset>
                <legend><?= __('Add Parte Entrada Registro') ?></legend>
                <?php
                    echo $this->Form->control('parte_entrada_id', ['options' => $parteEntradas, 'empty' => true]);
                    echo $this->Form->control('item_id', ['options' => $items, 'empty' => true]);
                    echo $this->Form->control('item_index');
                    echo $this->Form->control('cantidad');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
