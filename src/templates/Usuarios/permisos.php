<?php
echo $this->Form->create($usuario, ['id' => "formUsuariosAddPerfil"]);
$this->Form->setTemplates(['inputContainer' => '{{content}}'])
?>
<div class="">
    <div class="col-md-8 offset-2">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4><?= $usuario->nombre ?></h4>
                <h6><?= $usuario->rol ?></h6>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card" style="min-height: 450px;">
            <div class="card-header">Permisos del Sistema</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 px-1">
                        <div class="input-group input-group-sm">
                            <select class="form-control"  id="filtroPerfiles" name="perfil_id" >
                                <option value="" disabled >--Seleccione algun perfil--</option>
                                <?php foreach ($perfiles as $perfil):?>
                                    <option  <?= $perfil->id == $usuario->perfil_id ? 'selected' : ""?>  value='<?=$perfil->id?>'><?=$perfil->nombre?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 px-1">
                        <input id="filtroPermisos"  type="text" class="form-control form-control-sm" placeholder="Filtro de Permisos" />
                    </div>
                </div>
                <div class="mt-3">
                    <label id="label_aux_perfil">Permisos de Super Administrador.</label>
                    <div id="UsuarioPermisosList">
                        <div style="overflow-y: auto; height: 45vh">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary btn-sm" id="check_todos">
                    Marcar todos los permisos
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="uncheck_todos">
                    Desmarcar todos los permisos
                </button>
            </div>
        </div>
        <div class="px-2 d-flex align-items-center" style="max-width:100px">
            <div>
                <button type="button" class="btn btn-primary" onclick="asignarPermisosUsuario()">
                    <i class="fas fa-arrow-right"></i>
                </button>
                <br><br>
                <button type="button" class="btn btn-primary" onclick="aumentarPermisosUsuario()">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Permisos Asignados</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 px-1">
                        <input id="filtroPermisosAsignados"  type="text" class="form-control form-control-sm" placeholder="Filtro" />
                    </div>
                </div>
                <div class="mt-3">
                    <div id="UsuarioPermisosAsignadosList">
                        <div style="overflow-y: auto; height: 45vh">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary btn-sm" id="drop_todos">
                    Quitar todos los permisos
                </button>
            </div>
        </div>
    </div>
    <div class="pt-3">
        <button class="btn btn-sm btn-outline-primary">
            <i class="fas fa-save fa-fw"></i> Guardar
        </button>
    </div>
</div>
<?= $this->Form->end() ?>
<?= $this->Html->scriptBlock('var permisos_generales = ' . json_encode($permisos));?>
<?= $this->Html->scriptBlock('var permisos_check_usuario = ' . json_encode($permisos_check_usuario));?>

