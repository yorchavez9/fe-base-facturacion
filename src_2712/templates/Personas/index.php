<?php
/**
 * @var \App\View\AppView $this
 */
?>

<div class="">
    <?= $this->Form->create(null, ['method' => 'GET', 'id' => 'formFiltroPersonas']); ?>
    <div class="row align-items-center">
        <div class="col-3">
            <?= $this->Form->control("opt_dni", ["class" => "form-control form-control-sm", "placeholder" => "DNI", 'label' => false, 'value' => $opt_dni, 'autocomplete' => 'off']) ?>
        </div>
        <div class="col-3">
            <?= $this->Form->control("opt_nombre", ["class" => "form-control form-control-sm ", "placeholder" => "Nombre", 'label' => false, 'value' => $opt_nombre, 'autocomplete' => 'off']) ?></div>
        <div class="col-3">
            <?= $this->Form->control("opt_apellido", ["class" => "form-control form-control-sm ", "placeholder" => "Apellidos", 'label' => false, 'value' => $opt_apellido, 'autocomplete' => 'off']) ?></div>
        <div class="col-3">
            <div class="input-group">
                <button class="btn btn-sm btn-primary" type="submit">Buscar</button>
                <div class="input-group-append">
                    <button class="btn btn-sm btn-outline-primary " type="button" onclick="limpiar()">Limpiar</button>
                </div>
                <button type="submit" name="exportar" value="1" class="btn btn-sm btn-primary ml-2" title="Exportar">
                    <i class="fas fa-file-excel fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
    <br>
    <div class="table-responsive" style="min-height: 45vh;">
        <table class="table table-striped table-sm" id="checkTable">
            <thead>
            <tr>
                <th scope="col"><input id="checkAll" type="checkbox"> <?= $this->Paginator->sort('dni') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nombres') ?></th>
                <th scope="col"><?= $this->Paginator->sort('apellidos') ?></th>
                <th scope="col">Info Contacto</th>
                <th scope="col"><?= $this->Paginator->sort('created', ['label' => 'F. Registro']) ?></th>
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($personas as $persona): ?>
                <tr>
                    <td>
                        <div class="form-check">
                            <label>
                                <input class="form-check-input sel" type="checkbox" value="<?= $persona->id ?>" id="check<?= $persona->id ?>">
                                <?= $persona->dni ?>
                            </label>
                        </div>
                    </td>
                    <td><?= h($persona->nombres) ?></td>
                    <td><?= h($persona->apellidos) ?></td>
                    <td>
                        <?php if ($persona->correo != ''):?>
                            <i class="fas fa-envelope fa-fw"></i> <?= $persona->correo?><br/>
                        <?php endif; ?>
                        <?php if ($persona->telefono != ''):?>
                            <i class="fas fa-phone fa-fw"></i> <?= $persona->telefono?><br/>
                        <?php endif; ?>
                        <?php if ($persona->celular_trabajo != ''):?>
                            <i class="fas fa-mobile fa-fw"></i> <?= $persona->celular_trabajo?><br/>
                        <?php endif; ?>
                        <?php if ($persona->celular_personal != ''):?>
                                <i class="fas fa-mobile fa-fw"></i> <?= $persona->celular_personal?><br/>
                        <?php endif; ?>
                        <?php if ($persona->whatsapp != ''):?>
                            <i class="fas fa-phone-square fa-fw"></i> <?= $persona->whatsapp?><br/>

                            <?php endif; ?>
                    </td>
                    <td><?= h($persona->created ? $persona->created->format("d/m/Y H:i:s") : "") ?></td>
                    <td class="actions px-3" style="width:150px">
                        <div class="dropdown">
                            <button id="dLabel" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dLabel">
                                <a class="dropdown-item-text" href="#" onclick="editarPersona(<?=$persona->id?>)"><i class="fas fa-pencil-alt"></i> Editar  </a>
                                <a class="dropdown-item-text" onclick ="eliminar(<?=$persona->id?>)" href="javascript:void(0)" ><i class="fas fa-trash fa-fw"></i> Eliminar</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <button class="btn btn-danger btn-sm" onclick="deleteAll()">
        <i class="fas fa-trash"></i>
        Borrar seleccionados
    </button>

    <?= $this->element('paginador') ?>
</div>


<?php
echo $this->Element('form_persona');
echo $this->Element('modal_mensaje_personas_whatsapp');
?>

