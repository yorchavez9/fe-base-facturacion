<div class="row pb-3">
    <div class="col-md-12">
        <?php
        echo $this->Form->create(null, ['method' => 'GET']);
        $this->Form->setTemplates(['inputContainer' => '{{content}}']);
        ?>
        <div class="row align-items-end">
            <div class="col-md-8 mb-2">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search fa-fw"></i>
                        </span>
                    </div>
                    <input placeholder="Encuentre usuarios por nombre" type="text" name="q_nombre"
                           class="form-control form-control-sm" value="<?= $nombre ?>"/>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <button type="submit" value="Encontrar" class="btn btn-sm btn-primary">Buscar</button>
                <button type="submit" name="exportar" value="1" class="btn btn-sm btn-success">Exportar</button>
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive gw-table">
            <table class="table table-sm table-striped">
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('nombre', ['label' => 'Nombre']) ?></th>
                    <th><?= $this->Paginator->sort('usuario', ['label' => 'Usuario']) ?></th>
                    <th><?= $this->Paginator->sort('rol', ['label' => 'Cargo']) ?></th>
                    <th><?= $this->Paginator->sort('correo', ['label' => 'Correo Electrónico']) ?></th>
                    <th><?= $this->Paginator->sort('celular', ['label' => 'Celular']) ?></th>
                    <th><?= $this->Paginator->sort('created', ['label' => 'Fec. Registro']) ?></th>
                    <th class="actions">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <?php
                    if ($usuario->rol == "SUPERADMIN" && $usuario_sesion['rol'] != "SUPERADMIN"):
                        continue;
                    endif;
                    ?>
                    <tr>
                        <td><?= $usuario->nombre ?></td>
                        <td><?= h($usuario->usuario) ?></td>
                        <td><?= $usuario->rol ?></td>
                        <td><?= $usuario->correo ?></td>
                        <td><?= $usuario->celular ?></td>
                        <td><?= h($usuario->created->format("d/m/Y")) ?></td>

                            <td class="actions" width="150px">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width:200px">
                                        <?php
                                            echo "<a href='javascript:FormUsuarios.Editar($usuario->id)' class='dropdown-item-text'><i class='fa fa-edit fa-fw'></i> Modificar</a>";
                                            echo "<a href='javascript:cambiarClave(`$usuario->id`, `$usuario->usuario`)' class='dropdown-item-text'><i class='fa fa-edit fa-fw'></i> Modificar Clave</a>";
                                            echo $this->Form->postLink("<i class='fa fa-trash fa-fw'></i> Eliminar", ['action' => 'delete', $usuario->id], ['confirm' => __('¿Está seguro que quiere eliminar a {0}?', $usuario->nombre), 'class' => 'dropdown-item-text', 'escape' => false]);
                                        ?>
                                    </div>
                                </div>
                            </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= $this->element("paginador");?>
    </div>
</div>
<?php
echo $this->Element('form_usuario');
echo $this->Element('cambiar_clave_usuario');
?>
