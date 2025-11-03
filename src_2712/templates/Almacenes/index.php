<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create(null, ['method' => 'GET']) ?>
        <div class="input-group mb-3">
            <input placeholder="Encuentre almacenes por nombre" type="text" name="opt_nombre" class="form-control form-control-sm" value="<?= $opt_nombre ?>" />
            <div class="input-group-append">
                <input type="submit" value="Encontrar" class="btn btn-primary btn-sm" />
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
    <div class="col-md-12">
        <div class="table-responsive gw-table">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre </th>
                        <th>Telefono </th>
                        <th>Correo </th>
                        <th>Dirección </th>
                        <th class="actions">Acciones</th>
                    </tr>
                </thead>
                <tbody id="table_data">
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->Html->scriptBlock(" var ubicaciones = " . json_encode($ubicaciones)); ?>

<div class="modal fade" id="modalEstablecimiento" data-keyboard="false" tabindex="-1" aria-labelledby="modalEstablecimientoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEstablecimientoLabel">Establecimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <?= $this->Form->create(null, ['onsubmit' => 'Establecimientos.save(event)' ,'id' => 'formEstablecimiento']);
                $this->Form->setTemplates(['inputContainer' => '{{content}}']);
                ?>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-code fa-fw"></i>&nbsp;&nbsp;&nbsp;
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'codigo',
                                    [
                                        'class' => 'form-control form-control-sm letras',
                                        'autocomplete' => 'off',
                                        'required' => true,
                                        'label' => false,
                                        'placeholder' => 'Código'
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-warehouse fa-fw"></i>&nbsp;&nbsp;&nbsp;
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'nombre',
                                    [
                                        'class' => 'form-control form-control-sm letras',
                                        'autocomplete' => 'off',
                                        'required' => true,
                                        'label' => false,
                                        'placeholder' => 'Nombre'
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <div class="form-row">
                        <div class="col-sm-6 col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-phone fa-fw"></i>
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'telefono',
                                    [
                                        'class' => 'form-control form-control-sm telefono',
                                        'placeholder' => 'Teléfono',
                                        'autocomplete' => 'off',
                                        'label' => false,
                                        'required' => true,
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-envelope fa-fw"></i>
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'correo',
                                    [
                                        'class' => 'form-control form-control-sm email',
                                        'placeholder' => 'Correo',
                                        'autocomplete' => 'off',
                                        'type' => 'email',
                                        'label' => false,
                                        'required' => true,
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-map-marker-alt fa-fw"></i>
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'ubigeo_dpr',
                                    [
                                        'label' => 'Ubicación',
                                        'placeholder' => 'Ubicacion',
                                        'type' => 'text',
                                        'class' => 'form-control form-control-sm',
                                        'autocomplete' => 'off',
                                        'label' => false,
                                        'required' => true,
                                    ]
                                ) ?>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-home fa-fw"></i>
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'urbanizacion',
                                    [
                                        'label' => 'Urbanización',
                                        'type' => 'text',
                                        'placeholder' => 'Urbanizacion',
                                        'class' => 'form-control form-control-sm',
                                        'autocomplete' => 'off',
                                        'label' => false,
                                        'required' => true,
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-marker fa-fw"></i>&nbsp;&nbsp;&nbsp; Dirección
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'direccion',
                                    [
                                        'type' => 'text',
                                        'placeholder' => 'Direccion',
                                        'class' => 'form-control form-control-sm',
                                        'autocomplete' => 'off',
                                        'label' => false,
                                        'required' => true,
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <?= $this->Form->control('id', ['type' => 'hidden', 'value' => "0"]) ?>
                            <?= $this->Form->control('map_lat', ['type' => 'hidden']) ?>
                            <?= $this->Form->control('map_len', ['type' => 'hidden']) ?>
                            <?= $this->Form->control('ubigeo', ['type' => 'hidden']) ?>
                            <?= $this->Form->control('departamento', ['type' => 'hidden']) ?>
                            <?= $this->Form->control('provincia', ['type' => 'hidden']) ?>
                            <?= $this->Form->control('distrito', ['type' => 'hidden']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                <button type="submit" id="btn_guardar" class="btn btn-primary">Guardar</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>