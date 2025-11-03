<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteEntrada $parteEntrada
 * @var string[]|\Cake\Collection\CollectionInterface $parteSalidas
 * @var string[]|\Cake\Collection\CollectionInterface $almacenes
 * @var string[]|\Cake\Collection\CollectionInterface $usuarios
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $parteEntrada->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $parteEntrada->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Parte Entradas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteEntradas form content">
            <?= $this->Form->create($parteEntrada) ?>
            <fieldset>
                <legend><?= __('Edit Parte Entrada') ?></legend>
                <?php
                    echo $this->Form->control('parte_salida_id', ['options' => $parteSalidas, 'empty' => true]);
                    echo $this->Form->control('fecha', ['empty' => true]);
                    echo $this->Form->control('descripcion');
                    echo $this->Form->control('almacen_id', ['options' => $almacenes, 'empty' => true]);
                    echo $this->Form->control('usuario_id', ['options' => $usuarios, 'empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
