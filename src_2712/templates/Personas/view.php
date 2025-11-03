<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Persona $persona
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Persona'), ['action' => 'edit', $persona->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Persona'), ['action' => 'delete', $persona->id], ['confirm' => __('Are you sure you want to delete # {0}?', $persona->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Personas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Persona'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="personas view content">
            <h3><?= h($persona->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Dni') ?></th>
                    <td><?= h($persona->dni) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nombres') ?></th>
                    <td><?= h($persona->nombres) ?></td>
                </tr>
                <tr>
                    <th><?= __('Apellidos') ?></th>
                    <td><?= h($persona->apellidos) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cargo Empresa') ?></th>
                    <td><?= h($persona->cargo_empresa) ?></td>
                </tr>
                <tr>
                    <th><?= __('Correo') ?></th>
                    <td><?= h($persona->correo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefono') ?></th>
                    <td><?= h($persona->telefono) ?></td>
                </tr>
                <tr>
                    <th><?= __('Anexo') ?></th>
                    <td><?= h($persona->anexo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Celular Trabajo') ?></th>
                    <td><?= h($persona->celular_trabajo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Celular Personal') ?></th>
                    <td><?= h($persona->celular_personal) ?></td>
                </tr>
                <tr>
                    <th><?= __('Info Busqueda') ?></th>
                    <td><?= h($persona->info_busqueda) ?></td>
                </tr>
                <tr>
                    <th><?= __('Direccion') ?></th>
                    <td><?= h($persona->direccion) ?></td>
                </tr>
                <tr>
                    <th><?= __('Clave') ?></th>
                    <td><?= h($persona->clave) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($persona->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($persona->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($persona->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Cuenta Personas') ?></h4>
                <?php if (!empty($persona->cuenta_personas)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Cuenta Id') ?></th>
                            <th><?= __('Persona Id') ?></th>
                            <th><?= __('Cargo') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($persona->cuenta_personas as $cuentaPersonas) : ?>
                        <tr>
                            <td><?= h($cuentaPersonas->id) ?></td>
                            <td><?= h($cuentaPersonas->cuenta_id) ?></td>
                            <td><?= h($cuentaPersonas->persona_id) ?></td>
                            <td><?= h($cuentaPersonas->cargo) ?></td>
                            <td><?= h($cuentaPersonas->created) ?></td>
                            <td><?= h($cuentaPersonas->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'CuentaPersonas', 'action' => 'view', $cuentaPersonas->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'CuentaPersonas', 'action' => 'edit', $cuentaPersonas->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'CuentaPersonas', 'action' => 'delete', $cuentaPersonas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cuentaPersonas->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
