<div class="row">
    <div class="col">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" >

                <tr>
                    <td width="200px">RAZON SOCIAL</td>
                    <td colspan="5"><b><?= $cliente->razon_social ?> // <?= $cliente->nombre_comercial ?></b></td>
                </tr>
                <tr>
                    <td>DOMICILIO FISCAL</td>
                    <td colspan="5"><?= $cliente->domicilio_fiscal ?></td>
                </tr>
                <tr>
                    <td style="width: 25%;">RUC</td>
                    <td style="width: 25%;" colspan="2"><?= $cliente->ruc ?></td>
                    <td style="width: 25%;" >UBIGEO</td>
                    <td style="width: 25%;" colspan="2"><?= $cliente->ubigeo_dpr ?></td>
                </tr>
                <tr>
                    <td>F. CREACIÓN</td>
                    <td colspan="2"><?= $cliente->created ? $cliente->created->format('Y/m/d h:m:s A') : "" ?></td>
                    <td width="150px">ESTADO</td>
                    <td colspan="2"><?= $cliente->estado ?></td>
                </tr>
                <tr>
                    <td>TELEFONO</td>
                    <td colspan="2"><?= $cliente->telefono ?></td>
                    <td width="150px">CELULAR</td>
                    <td colspan="2"><?= $cliente->celular ?></td>
                </tr>
                <tr>
                    <td>CORREO</td>
                    <td colspan="4"><?= $cliente->correo ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="">
    <?= $this->Form->create(null, ['method' => 'GET']); ?>
    <div class="row align-items-center">
        <div class="col-sm-6 col-md-3 mb-3">
            <?= $this->Form->control("opt_dni", ["class" => "form-control form-control-sm", "placeholder" => "DNI", 'label' => false, 'value' => $opt_dni]) ?>
        </div>
        <div class="col-sm-6 col-md-3 mb-3">
            <?= $this->Form->control("opt_nombre", ["class" => "form-control form-control-sm", "placeholder" => "Nombres", 'label' => false, 'value' => $opt_nombre]) ?></div>
        <div class="col-sm-6 col-md-3 mb-3">
            <?= $this->Form->control("opt_apellido", ["class" => "form-control form-control-sm", "placeholder" => "Apellidos", 'label' => false, 'value' => $opt_apellido]) ?></div>
        <div class="col-sm-6 col-md-3 mb-3">

            <div class="input-group">
                <button class="btn btn-sm btn-primary" type="submit">Buscar</button>
                <div class="input-group-append">
                    <button class="btn btn-sm btn-outline-primary " type="button" onclick="limpiar()">Limpiar</button>
                </div>
            </div>

        </div>
    </div>
    <?= $this->Form->end(); ?>
    <br>
    <div class="table table-responsive gw-table">
        <table class="table table-sm table-striped" id="checkTable">
            <thead>
            <tr>

                <th scope="col">
                    <input id="checkAll" type="checkbox">
                    <?= $this->Paginator->sort('dni') ?>
                </th>
                <th scope="col"><?= $this->Paginator->sort('nombres') ?></th>
                <th scope="col"><?= $this->Paginator->sort('apellidos') ?></th>
                <th scope="col"><?= $this->Paginator->sort('correo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('telefono') ?></th>
                <th scope="col"><?= $this->Paginator->sort('celular_trabajo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('celular_personal') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created', ['label' => 'F. Creación']) ?></th>
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
                    <td><?= h($persona->correo) ?></td>
                    <td><?= h($persona->telefono) ?></td>
                    <td><?= h($persona->celular_trabajo) ?></td>
                    <td><?= h($persona->celular_personal) ?></td>
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

<div class="modal fade" id="modalClave" tabindex="-1" role="dialog" aria-labelledby="Clave" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['class' => 'form']); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Clave</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span> <!-- icono de la esquina -->
                </button>
            </div>
            <div class="modal-body">
                <label>Ingrese una Clave</label>  <button class="btn btn-primary btn-sm mx-2" onclick="generarClave()" type="button"> Generar Clave </button>
                <input type="text" class="form-control" name="clave" value="">
                <input type="hidden" name="id" value="" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
                <button type="button" id="claveGuardar" class="btn btn-primary">Guardar</button>
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php
echo $this->Element('form_persona');
echo $this->Html->ScriptBlock("var cuenta_id = '" . $cliente->id . "';");
?>

