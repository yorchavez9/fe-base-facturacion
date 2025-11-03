<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ParteSalidaRegistro $parteSalidaRegistro
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Parte Salida Registro'), ['action' => 'edit', $parteSalidaRegistro->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Parte Salida Registro'), ['action' => 'delete', $parteSalidaRegistro->id], ['confirm' => __('Are you sure you want to delete # {0}?', $parteSalidaRegistro->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Parte Salida Registros'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Parte Salida Registro'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="parteSalidaRegistros view content">
            <h3><?= h($parteSalidaRegistro->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Parte Salida') ?></th>
                    <td><?= $parteSalidaRegistro->has('parte_salida') ? $this->Html->link($parteSalidaRegistro->parte_salida->id, ['controller' => 'ParteSalidas', 'action' => 'view', $parteSalidaRegistro->parte_salida->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Item') ?></th>
                    <td><?= $parteSalidaRegistro->has('item') ? $this->Html->link($parteSalidaRegistro->item->id, ['controller' => 'Items', 'action' => 'view', $parteSalidaRegistro->item->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($parteSalidaRegistro->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Item Index') ?></th>
                    <td><?= $this->Number->format($parteSalidaRegistro->item_index) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cantidad') ?></th>
                    <td><?= $this->Number->format($parteSalidaRegistro->cantidad) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($parteSalidaRegistro->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($parteSalidaRegistro->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
