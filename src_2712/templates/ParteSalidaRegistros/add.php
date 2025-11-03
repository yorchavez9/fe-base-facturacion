<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteSalidaRegistro $parteSalidaRegistro
 * @var \Cake\Collection\CollectionInterface|string[] $parteSalidas
 * @var \Cake\Collection\CollectionInterface|string[] $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Parte Salida Registros'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteSalidaRegistros form content">
            <?= $this->Form->create($parteSalidaRegistro) ?>
            <fieldset>
                <legend><?= __('Add Parte Salida Registro') ?></legend>
                <?php
                    echo $this->Form->control('parte_salida_id', ['options' => $parteSalidas, 'empty' => true]);
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
