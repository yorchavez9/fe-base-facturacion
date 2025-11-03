<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteEntradaRegistro $parteEntradaRegistro
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Parte Entrada Registro'), ['action' => 'edit', $parteEntradaRegistro->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Parte Entrada Registro'), ['action' => 'delete', $parteEntradaRegistro->id], ['confirm' => __('Are you sure you want to delete # {0}?', $parteEntradaRegistro->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Parte Entrada Registros'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Parte Entrada Registro'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteEntradaRegistros view content">
            <h3><?= h($parteEntradaRegistro->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Parte Entrada') ?></th>
                    <td><?= $parteEntradaRegistro->has('parte_entrada') ? $this->Html->link($parteEntradaRegistro->parte_entrada->id, ['controller' => 'ParteEntradas', 'action' => 'view', $parteEntradaRegistro->parte_entrada->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Item') ?></th>
                    <td><?= $parteEntradaRegistro->has('item') ? $this->Html->link($parteEntradaRegistro->item->id, ['controller' => 'Items', 'action' => 'view', $parteEntradaRegistro->item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($parteEntradaRegistro->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Item Index') ?></th>
                    <td><?= $this->Number->format($parteEntradaRegistro->item_index) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cantidad') ?></th>
                    <td><?= $this->Number->format($parteEntradaRegistro->cantidad) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($parteEntradaRegistro->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($parteEntradaRegistro->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
