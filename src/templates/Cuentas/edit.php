<?php
echo $this->Form->create($cliente, ['class' => 'form']);
$this->Form->setTemplates(['inputContainer' => '{{content}}'])
?>
<div class="row">
    <div class="col-sm-12 col-md-6 mb-3">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">RUC</label>
            </div><?= $this->Form->control('ruc',['label' => false, 'class' => 'form-control form-control-sm ruc','placeholder' => 'Ingrese DNI o RUC','required' => false]) ?>
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" onclick="consultaDniRucAjax()"><span class="estado_busqueda">Extraer de Sunat</span></button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6 mb-3">
        <div class="form-group mb-0">
            <?= $this->Form->control('razon_social',['label' => "Razón Social", 'class' => 'form-control form-control-sm', 'placeholder' => 'Razón Social']) ?>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 mb-3">
        <div class="form-group mb-0">
            <?= $this->Form->control('nombre_comercial',['label' => "Nombre Comercial", 'class' => 'form-control form-control-sm', 'placeholder' => 'Nombre Comercial']) ?>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 mb-3">
        <label for="">Canal de llegada</label>
        <div class="input-group">
            <?= $this->Form->control('canal_llegada_id', ['class' => 'form-control form-control-sm', 'options' => $canales, 'empty' => '-- Seleccione --','label'=>false]); ?>
            <div class="input-group-append ">

                <button onclick="addCanalLlegada()" class="btn btn-sm btn-primary" type="button"> <i class="fas fa-plus" ></i></button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 mb-3">
        <label for="">Tipo de cliente</label>
        <div class="input-group">
            <?= $this->Form->control('cliente_tipo_id', ['class' => 'form-control form-control-sm', 'options' => $cliente_tipos, 'empty' => '-- Seleccione --','label'=>false]); ?>
            <div class="input-group-append ">

                <button onclick="addTipoCliente()" class="btn btn-sm btn-primary" type="button"> <i class="fas fa-plus"></i></i></button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 mb-3">
        <div class="form-group mb-0">
            <?= $this->Form->control('domicilio_fiscal',['class' => 'form-control form-control-sm', 'placeholder' => 'Domicilio Fiscal']) ?>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 mb-3">
        <div class="form-group mb-0">
            <label class="control-label">Distrito / Provincia / Región</label>
            <?= $this->Form->control('ubigeo_dpr',['label' => false, 'class' => 'form-control form-control-sm letras', 'placeholder' => 'Ubicación']) ?>
            <?= $this->Form->control('ubigeo',['type' => 'hidden']) ?>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <label>Teléfono</label>
        <?= $this->Form->control('telefono',['label' => false, 'class' => 'form-control form-control-sm', 'placeholder' => 'Teléfono', 'autocomplete' => 'off']) ?>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <label>Celular</label>
        <?= $this->Form->control('celular',['label' => false, 'class' => 'form-control form-control-sm', 'placeholder' => 'Celular', 'autocomplete' => 'off']) ?>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <label>Whatsapp</label>
        <?= $this->Form->control('whatsapp',['label' => false, 'class' => 'form-control form-control-sm', 'placeholder' => 'Whatsapp', 'autocomplete' => 'off']) ?>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <label>Correo</label>
        <?= $this->Form->control('correo',['label' => false,'type'=>'email' ,'class' => 'form-control form-control-sm', 'placeholder' => 'Correo', 'autocomplete' => 'off']) ?>
    </div>

    <div class="col-md-12 mb-3">
        <div class="form-group mb-0">
            <?= $this->Form->control('notas',[ 'label' => 'Actividad de la empresa' , 'class' => 'form-control form-control-sm','placeholder'=>'Actividad de la Empresa', 'required' => false]) ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fa fa-users"></i>
        Contactos
        &nbsp;
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="FormPersona.NuevoParaCuenta(<?= $cliente->id ?>)">
            <i class="fa fa-plus"></i>
            Contacto
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Cargo</th>
                    <th>Teléfono</th>
                    <th>Whatsapp</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="tablaContactos">
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="form-group mt-3">
    <div class="row">
        <div class="col-md-4">
            <button type="submit" class="btn btn-sm btn-primary"><i class='fa fa-save'></i> Guardar</button>
            <input type="hidden" name="cliente_id" value="<?= $cliente->id?>" />
        </div>
    </div>
</div>
<?= $this->Form->end() ?>


<div id="modalBuscador" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Encuentre Clientes</h4>
            </div>
            <div  class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>RUC</th>
                        <th>Razón Social</th>
                        <th>Nombre Comercial</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?= $this->Html->scriptBlock(" var ubicaciones = " . json_encode($ubicaciones));?>
<div class="modal" tabindex="-1" id="canalLlegadaModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar canal de llegada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->Form->control("canal_llegada",['class'=>'form-control'])?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardarCanalLlegada()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" id="tipoClienteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar tipo de cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->Form->control("tipo_cliente",['class'=>'form-control'])?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardarTipoCliente()">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php echo $this->element("form_persona");
