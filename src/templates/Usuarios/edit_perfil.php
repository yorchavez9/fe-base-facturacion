<?php
echo $this->Form->create($perfil, ['id' => "formUsuariosEditPerfil"]);
$this->Form->setTemplates(['inputContainer' => '{{content}}'])
?>
<div class="row">
    <div class="col-md-8 offset-2">
        <div class="row pb-3">
            <div class="col-md-12">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-users fa-fw"></i>
                        </span>
                    </div>
                    <?= $this->Form->control('nombre', [ 'label' => false, 'placeholder' => 'Nombre', 'class' => 'form-control', 'required' => true, 'autocomplete' => 'off']); ?>
                </div>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-md-12">
                <input id="filtroPermisos"  type="text" class="form-control form-control-sm" placeholder="Filtro de Permisos" />
                <br/>
                <label class="">
                    <input type="checkbox" name="todos" value="*" id="check_gral" <?= $check_todos ? 'checked' : '' ?> />
                    Marcar todos
                </label>
                <hr class="mt-1">
                <div class="gw-table" id="list_perfiles" style=" <?= $check_todos ? 'display:none' : '' ?>">
                    <ul>
                        <?php
                            foreach ($permisos as $permiso):
                                $checked = ($permiso->check == '1') ? 'checked' : "";
                            ?>
                            <li>
                                <label class="filtrar">
                                    <input type="checkbox" name="permisos[]" value="<?=$permiso->codigo?>" <?= $checked ?> />
                                    <?= $permiso->nombre?>
                                </label>
                            </li>
                        <?php endforeach;  ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-md-12">
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-save fa-fw"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
