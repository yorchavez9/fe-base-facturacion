<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteSalidaRegistro $parteSalidaRegistro
 * @var string[]|\Cake\Collection\CollectionInterface $parteSalidas
 * @var string[]|\Cake\Collection\CollectionInterface $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $parteSalidaRegistro->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $parteSalidaRegistro->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Parte Salida Registros'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteSalidaRegistros form content">
            <?= $this->Form->create($parteSalidaRegistro) ?>
            <fieldset>
                <legend><?= __('Edit Parte Salida Registro') ?></legend>
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
