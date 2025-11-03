<?php
echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']);
$this->Form->setTemplates(['inputContainer' => '{{content}}']);

?>

<legend>WhatsApp</legend>

<div class="row">
    <div class="col-sm-12 col-md-6 mb-2">
        <label>Envio de Doc por WhatsApp</label>
        <select name="whatsapp_active" class="form-control">
            <option value="0" <?= $vars['whatsapp_active'] == "0" ? "selected" : "" ?>>Desactivado</option>
            <option value="1" <?= $vars['whatsapp_active'] == "1" ? "selected" : "" ?>>Activado</option>
        </select>
    </div>
    <div class="col-sm-12 col-md-6 mb-2">
        <label class="control-label">Endpoint Mensajes Whatsapp</label>
        <?= $this->Form->control("whatsapp_api_url_mensajes", ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control', 'value' => $vars['whatsapp_api_url_mensajes']]) ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 mb-2">
        <label class="control-label">Token Api Whatsapp</label>
        <?= $this->Form->control("whatsapp_api_token", ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control',  'value' => $vars['whatsapp_api_token']]) ?>
    </div>
    <div class="col-sm-12 col-md-6 mb-2">
        <label class="control-label">Endpoint Adjuntos Whatsapp</label>
        <?= $this->Form->control("whatsapp_api_url_adjunto", ['autocomplete' => 'off', 'label' => false, 'class' => 'form-control', 'value' => $vars['whatsapp_api_url_adjunto']]) ?>
    </div>
</div>
<br>

<legend>Mensaje de Prueba</legend>
<div class="row">
    <div class="col-sm-6 col-lg-4 mb-2">
        <label for="whatsapp_numero_prueba">Numero</label>
        <input class=" form-control form-control-sm" type="number" placeholder="Cod + Numero" name="whatsapp_numero_prueba" id="whatsapp_numero_prueba">
    </div>
    <div class="col-sm-6 col-lg-4 mb-2">
        <label for="whatsapp_mensaje_prueba">Mensaje</label>
        <input class="form-control form-control-sm" type="text" name="whatsapp_mensaje_prueba" placeholder="Mensaje" id="whatsapp_mensaje_prueba">
    </div>
    <div class="col-sm-6 col-lg-4 d-flex mb-2">
        <button type="submit" class="btn btn-sm text-light  mt-auto align-self-start" onclick="enviarMensajePrueba(event)" style="background-color: #2BCF8C"> <i class=" fa fa-fw fa-paper-plane"></i> Enviar</button>
    </div>
</div>
<br>

<legend>Correo de Prueba</legend>
<div class="row">
    <div class="col-sm-6 col-lg-4 mb-2">
        <label for="whatsapp_numero_prueba">Correo Destino</label>
        <input class=" form-control form-control-sm" placeholder="Correo" name="correo_prueba_destino" id="correo_prueba_destino">
    </div>
    <div class="col-sm-6 col-lg-4 mb-2">
        <label for="whatsapp_mensaje_prueba">Mensaje</label>
        <input class="form-control form-control-sm" type="text" name="correo_prueba_mensaje" placeholder="Mensaje" id="correo_prueba_mensaje">
    </div>
    <div class="col-sm-6 col-lg-4 d-flex mb-2">
        <button type="submit" class="btn btn-sm text-light  mt-auto align-self-start" onclick="enviarCorreoPrueba(event)" style="background-color: #2BCF8C"> <i class=" fa fa-fw fa-envelope"></i> Enviar</button>
    </div>
</div>

<br>

<legend>Servicio de Correo</legend>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-9 mb-2">
        <label for="whatsapp_numero_prueba">Servidor SMTP</label>
        <input class=" form-control form-control-sm" placeholder="Servidor SMTP Ejm.: ssl://smtp.gmail.com" name="config_email_smpt" id="config_email_smpt" value="<?= $vars['config_email_smpt'] ?? '' ?>">
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
        <label for="whatsapp_numero_prueba">Puerto del Servidor SMTP</label>
        <input class=" form-control form-control-sm" placeholder="Pueto Ejm.: 565" name="config_email_puerto" id="config_email_puerto" value="<?= $vars['config_email_puerto'] ?? '' ?>">
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
        <label for="whatsapp_mensaje_prueba">Usuario</label>
        <input class="form-control form-control-sm" type="text" name="config_email_usuario" placeholder="Usuario" id="config_email_usuario" value="<?= $vars['config_email_usuario'] ?? '' ?>">
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
        <label for="whatsapp_mensaje_prueba">Contraseña</label>
        <input class="form-control form-control-sm" type="text" name="config_email_clave" placeholder="Contraseña" id="config_email_clave" value="<?= $vars['config_email_clave'] ?? '' ?>">
    </div>
    <div class="col-sm-12 mb-2">
        <label for="whatsapp_mensaje_prueba">Nombre para mostrar</label>
        <input class="form-control form-control-sm" type="text" name="config_email_nombre" placeholder="Nombre para mostrar" id="config_email_nombre" value="<?= $vars['config_email_nombre'] ?? '' ?>">
    </div>
</div>
<br>
<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-save"></i> Guardar</button>

<?php echo $this->Form->end(); ?>

<br>
