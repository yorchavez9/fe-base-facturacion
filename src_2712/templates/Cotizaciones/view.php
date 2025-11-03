<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cotizacione $cotizacione
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Cotizacione'), ['action' => 'edit', $cotizacione->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Cotizacione'), ['action' => 'delete', $cotizacione->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cotizacione->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Cotizaciones'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Cotizacione'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="cotizaciones view content">
            <h3><?= h($cotizacione->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Usuario') ?></th>
                    <td><?= $cotizacione->has('usuario') ? $this->Html->link($cotizacione->usuario->id, ['controller' => 'Usuarios', 'action' => 'view', $cotizacione->usuario->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Cuenta') ?></th>
                    <td><?= $cotizacione->has('cuenta') ? $this->Html->link($cotizacione->cuenta->id, ['controller' => 'Cuentas', 'action' => 'view', $cotizacione->cuenta->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Venta') ?></th>
                    <td><?= $cotizacione->has('venta') ? $this->Html->link($cotizacione->venta->id, ['controller' => 'Ventas', 'action' => 'view', $cotizacione->venta->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Estado') ?></th>
                    <td><?= h($cotizacione->estado) ?></td>
                </tr>
                <tr>
                    <th><?= __('Codvendedor') ?></th>
                    <td><?= h($cotizacione->codvendedor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($cotizacione->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Almacen Id') ?></th>
                    <td><?= $this->Number->format($cotizacione->almacen_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Subtotal') ?></th>
                    <td><?= $this->Number->format($cotizacione->subtotal) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total') ?></th>
                    <td><?= $this->Number->format($cotizacione->total) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fecha Cotizacion') ?></th>
                    <td><?= h($cotizacione->fecha_cotizacion) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($cotizacione->created) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comentarios') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($cotizacione->comentarios)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
