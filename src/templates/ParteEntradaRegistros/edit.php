<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteEntradaRegistro $parteEntradaRegistro
 * @var string[]|\Cake\Collection\CollectionInterface $parteEntradas
 * @var string[]|\Cake\Collection\CollectionInterface $items
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $parteEntradaRegistro->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $parteEntradaRegistro->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Parte Entrada Registros'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteEntradaRegistros form content">
            <?= $this->Form->create($parteEntradaRegistro) ?>
            <fieldset>
                <legend><?= __('Edit Parte Entrada Registro') ?></legend>
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
