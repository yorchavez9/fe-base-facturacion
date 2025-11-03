<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteSalida $parteSalida
 * @var string[]|\Cake\Collection\CollectionInterface $usuarios
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $parteSalida->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $parteSalida->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Parte Salidas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteSalidas form content">
            <?= $this->Form->create($parteSalida) ?>
            <fieldset>
                <legend><?= __('Edit Parte Salida') ?></legend>
                <?php
                    echo $this->Form->control('almacen_salida_id');
                    echo $this->Form->control('almacen_destino_id');
                    echo $this->Form->control('usuario_id', ['options' => $usuarios, 'empty' => true]);
                    echo $this->Form->control('fecha_emision', ['empty' => true]);
                    echo $this->Form->control('descripcion');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
