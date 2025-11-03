<div class="container my-4">
    <div class="card" style="width: 450px; margin:auto">
        <div class="card-body">
            <h5 class="card-title">Datos del emisor</h5>
            <div>
                <p class="card-text">
                    <div>
                        RUC: <label for="" id="emisor_ruc"></label>
                    </div>
                    <div>
                        Raz. Social: <label for="" id="emisor_raz_social"></label>
                    </div>
                    <div>
                        Usuario secundario: <label for="" id="emisor_usuario_sec"></label>
                    </div>
                </p>
            </div>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalEmisor">
            <i class="fas fa-edit"></i> Editar emisor
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEmisor" tabindex="-1" aria-labelledby="modalEmisorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmisorLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['enctype' => 'multipart/form-data', 'onsubmit' => 'Emisor.sendData(event)' ,'id' => 'formEmisor']) ?>
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <div class="p-1">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Emisor RUC:</span>
                                </div>
                                <input class="form-control color" type="text" name="emisor_ruc" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-1">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Emisor Razon Social:</span>
                                </div>
                                <input class="form-control color" type="text" name="emisor_razon_social" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-1">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Usuario secundario:</span>
                                </div>
                                <input class="form-control color" type="text" name="emisor_usuario_sec" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-1">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Clave SOL:</span>
                                </div>
                                <input class="form-control color" type="text" name="emisor_clave_sol" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-1">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Subir certificado</span>
                                </div>
                                <input class="form-control color" type="file" name="certificado_pfx_ruta" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-1">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Clave del certificado:</span>
                                </div>
                                <input class="form-control color" type="text" name="emisor_certificado_clave" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->Form->button(__('<i class="fas fa-save"></i> Guardar'), ["class" => "btn btn-sm btn-primary" , 'id' => 'btn_guardar', 'escapeTitle' => false ]) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>