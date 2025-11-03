<div class="container">
    <?= $this->Form->create(null,['enctype' => 'multipart/form-data']) ?>
    <div class="row mb-3">
        <div class="col-sm-12 col-md-6 col-xl-3">
            <h6>Colores del Login</h6>
            <div class="row px-1">
                <div class="col-md-6 p-1 " >
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Fondo</span>
                        </div>
                        <input class="form-control color" type="text" name="login_fondo" value="<?= isset($vars['login_fondo']) ? $vars['login_fondo'] : "#ffffff" ?>">
                    </div>
                </div>
                <div class="col-md-6 p-1">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Texto</span>
                        </div>
                        <input class="form-control color" type="text" name="login_color_txt" value="<?= isset($vars['login_color_txt']) ? $vars['login_color_txt'] : "#000000" ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-3">
            <h6>Colores de Barra Superior</h6>
            <div class="row px-1">
                <div class="col-md-6 p-1">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Fondo</span>
                        </div>
                        <input class="form-control color" type="text" name="global_color_bg1" value="<?= $vars['global_color_bg1']?>">
                    </div>
                </div>
                <div class="col-md-6 p-1">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Texto</span>
                        </div>
                        <input class="form-control color" type="text" name="global_color_txt1" value="<?= $vars['global_color_txt1']?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-3">
            <h6>Colores de Barra Lateral</h6>
            <div class="row px-1">
                <div class="col-md-6 p-1">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Fondo</span>
                        </div>
                        <input class="form-control color" type="text" name="global_color_bg2" value="<?= $vars['global_color_bg2']?>">
                    </div>
                </div>
                <div class="col-md-6 p-1">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Texto</span>
                        </div>
                        <input class="form-control color" type="text" name="global_color_txt2" value="<?= $vars['global_color_txt2']?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-3">
            <div class="h6">Colores del titulo</div>
            <div class="row px-1">
                <div class="col-md-6 p-1">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Fondo</span>
                        </div>
                        <input class="form-control color" type="text" name="global_color_titulo_bg" value="<?= $vars['global_color_titulo_bg']?>">
                    </div>
                </div>
                <div class="col-md-6 p-1">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Texto</span>
                        </div>
                        <input class="form-control color" type="text" name="global_color_titulo_txt" value="<?= $vars['global_color_titulo_txt']?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-5 mb-2">
            <div class="">
                Logo<br>
                <img class="gw_buploader img img-fluid " src="<?= $this->Url->Build("/". $vars['global_logo'])?>" onclick="setImagenParaAdjuntar(this)">
                <input type="file" class="file_uploader" name="global_logo" style="display: none;">
            </div>
            <div class="">
                Favicon<br>
                <img class="gw_buploader img img-fluid " width="150px" src="<?= $this->Url->Build("/". $favicon)?>" onclick="setImagenParaAdjuntar(this)">
                <input type="file" class="file_uploader" name="favicon" style="display: none;">
            </div>
        </div>
        <div class="col-md-7">
            Fondo de Inicio de Sesión
            <img class="img img-fluid gw_buploader" src="<?= $this->Url->Build("/".$vars['global_fondo'])?>" onclick="setImagenParaAdjuntar(this)">
            <input type="file" class="file_uploader" name="global_fondo" style="display: none;">
        </div>
    </div>
    <?= $this->Form->button(__('Guardar'),["class" => "btn btn-sm btn-primary"]) ?>
    <?= $this->Form->end() ?>
</div>
