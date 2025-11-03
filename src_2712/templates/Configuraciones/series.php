<div class="row">
    <!-- <div class="col-md-12">
        <?= $this->Form->create(null, ['method' => 'GET']) ?>
        <div class="input-group mb-3">
            <input placeholder="Encuentre almacenes por nombre" type="text" name="opt_nombre" class="form-control form-control-sm" value="<?= $opt_nombre ?>" />
            <div class="input-group-append">
                <input type="submit" value="Encontrar" class="btn btn-primary btn-sm" />
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div> -->
    <div class="col-md-12">
        <div class="table-responsive gw-table">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Serie</th>
                        <th>Correlativo</th>
                        <th class="actions">Acciones</th>
                    </tr>
                </thead>
                <tbody id="table_data">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSeries" data-keyboard="false" tabindex="-1" aria-labelledby="modalSeriesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSeriesLabel">Serie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <?= $this->Form->create(null, ['onsubmit' => 'Series.save(event)' ,'id' => 'formSeries']);
                $this->Form->setTemplates(['inputContainer' => '{{content}}']);
                ?>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-code fa-fw"></i>
                                    </span>
                                </div>
                                <select name="tipo" id="" class="form-control" required>
                                    <option value="FACTURA">FACTURA ELECTRÓNICA</option>
                                    <option value="BOLETA">BOLETA DE VENTA ELECTRONICA</option>
                                    <option value="FACTURA_NC">NOTA DE CRÉDITO - FACTURA</option>
                                    <option value="BOLETA_NC">NOTA DE CRÉDITO - BOLETA</option>
                                    <option value="FACTURA_ND">NOTA DE DÉBITO - FACTURA</option>
                                    <option value="BOLETA_ND">NOTA DE DÉBITO - BOLETA</option>
                                    <option value="GRE_REMITENTE">GUIA DE REMISIÓN REMITENTE</option>
                                    <option value="GRE_TRANSPORTISTA">GUIA DE REMISIÓN TRANSPORTISTA</option>
                                </select>
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
                                        <i class="fas fa-font fa-fw"></i>
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'serie',
                                    [
                                        'class' => 'form-control form-control-sm',
                                        'placeholder' => 'Serie',
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
                                        <i class="fa fa-hashtag fa-fw"></i>
                                    </span>
                                </div>
                                <?= $this->Form->control(
                                    'correlativo',
                                    [
                                        'class' => 'form-control form-control-sm',
                                        'placeholder' => 'Correlativo',
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
                    <div class="form-row">
                        <div class="col-12">
                            <div class="">
                                <i class="fa fa-store fa-fw"></i> Establecimientos:                                
                            </div>
                            <div id="list_establecimientos" class="mt-3">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <?= $this->Form->control('id', ['type' => 'hidden', 'value' => "0"]) ?>
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