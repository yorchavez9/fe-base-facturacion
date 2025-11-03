<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsuarioPerfil[]|\Cake\Collection\CollectionInterface $usuarioPerfil
 */
?>
<div class="usuarioPerfil index content">
    <div class="table-responsive">
        <table class="table table-responsive-sm">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('nombre') ?></th>
                    <th><?= $this->Paginator->sort('permisos') ?></th>
                    <th><?= $this->Paginator->sort('created', 'Creado') ?></th>
                    <th class="actions"><?= __('Acciones') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarioPerfil as $usuarioPerfil): ?>
                <tr>
                    <td><?= $this->Number->format($usuarioPerfil->id) ?></td>
                    <td><?= $usuarioPerfil->nombre ?></td>
                    <td>
                        <a href="javascript:ModalPermisoPerfiles.abrir(<?=$usuarioPerfil->id?>,'<?=$usuarioPerfil->nombre?>')"> <i class="fa fa-eye fa-fw"></i> </a>
                    </td>
                    <td><?= h($usuarioPerfil->created ? $usuarioPerfil->created->format('d/m/Y H:m:s') : "") ?></td>
                    <td class="actions" style="min-width:180px;width:180px;">
                        <?= $this->Html->link("<i class='fas fa-pencil-alt fa-fw'></i> Editar", ['action' => 'editPerfil', $usuarioPerfil->id],['escape' => false]) ?> |
                        <?= $this->Form->postLink("<i class='fas fa-trash fa-fw'></i> Eliminar", ['action' => 'delPerfil', $usuarioPerfil->id], ['escape' => false, 'confirm' => __('¿Realmente desea eliminar el perfil {0}?', $usuarioPerfil->nombre)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element("paginador");?>
</div>

<?php
echo $this->Element('modal_permisos_perfiles');
?>
