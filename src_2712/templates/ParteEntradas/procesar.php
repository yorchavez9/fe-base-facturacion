<div class="d-flex justify-content-center">
    <div class="px-2">
        <a href="<?= $this->Url->build(['action' => 'imprimir-pdf', $parte_entrada->id]) ?>" class="btn btn-primary" target="_blank">
            <i class="fas fa-file-signature" style="font-size:5em"></i><br>
            Imprimir Parte
        </a>
    </div>
    <div class="px-2">
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-success">
            <i class="fas fa-list" style="font-size:5em"></i><br>
            Ir al listado
        </a>
    </div>
</div>
<!--
<div class="row pt-4">
    <div class="col-6">
        <?= $this->Form->create(null, ['id' => 'formCorreo']); ?>
        <div class="form-group">
            <label for="">Correo</label>
            <div class="input-group mb-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo" aria-label="Correo" aria-describedby="button-addon2" required>
                <div class="input-group-append">
                    <button class="btn btn-info" type="submit" name="btn_correo" value="1" id="btn_enviar">Enviar</button>
                </div>
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
    <div class="col-6">
        <?= $this->Form->create(null, ['id' => 'formCorreo']); ?>
        <div class="form-group">
            <label for="">Whatsapp</label>
            <div class="input-group mb-3">
                <input type="text" name="celular" id="whatsapp" class="form-control" placeholder="Whatsapp" aria-label="Whatsapp" aria-describedby="button-addon2" maxlength="11"  minlength="9" required>
                <div class="input-group-append">
                    <button class="btn btn-info" type="submit" name="btn_whatsapp" value="1" id="btn_enviar">Enviar</button>
                </div>
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>
-->
