<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create($almacen, ['class' => 'form']);
            $this->Form->setTemplates(['inputContainer' => '{{content}}']);
        ?>
        <div class="form-group">
            <div class="form-row">
                <div class="col-4">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >
                                <i class="fa fa-code fa-fw" ></i>&nbsp;&nbsp;&nbsp;
                            </span>
                        </div>
                        <?= $this->Form->control('codigo',
                            ['class' => 'form-control form-control-sm letras',
                                'autocomplete' => 'off','required'=>true,'label' =>false,
                                'placeholder' => 'Código']) ?>
                    </div>
                </div>
                <div class="col-8">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >
                                <i class="fa fa-warehouse fa-fw" ></i>&nbsp;&nbsp;&nbsp;
                            </span>
                        </div>
                        <?= $this->Form->control('nombre',
                            ['class' => 'form-control form-control-sm letras',
                                'autocomplete' => 'off','required'=>true,'label' =>false,
                                'placeholder' => 'Nombre']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <div class="form-row">
                <div class="col-sm-6 col-md-6 col-lg-3 mb-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >
                                <i class="fa fa-phone fa-fw" ></i>
                            </span>
                        </div>
                        <?= $this->Form->control('telefono',
                            ['class' => 'form-control form-control-sm telefono','placeholder' => 'Teléfono',
                                'autocomplete' => 'off', 'label' =>false]) ?>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 mb-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >
                                <i class="fa fa-envelope fa-fw" ></i>
                            </span>
                        </div>
                        <?= $this->Form->control('correo',
                            ['class' => 'form-control form-control-sm email', 'placeholder' => 'Correo',
                                'autocomplete' => 'off','type'=>'email',
                                'label' => false]) ?>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 mb-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >
                                <i class="fa fa-map-marker-alt fa-fw" ></i>
                            </span>
                        </div>
                        <?= $this->Form->control('ubigeo_dpr',
                            ['label' => 'Ubicación', 'placeholder'=> 'Ubicacion',
                                'type'=> 'text', 'class' => 'form-control form-control-sm',
                                'autocomplete' => 'off','label'=>false]) ?>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3 mb-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >
                                <i class="fa fa-home fa-fw" ></i>
                            </span>
                        </div>
                        <?= $this->Form->control('urbanizacion',
                            [
                                'label' => 'Urbanización',
                                'type'=> 'text','placeholder'=>'Urbanizacion',
                                'class' => 'form-control form-control-sm',
                                'autocomplete' => 'off','label'=>false]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-12">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >
                                <i class="fa fa-marker fa-fw" ></i>&nbsp;&nbsp;&nbsp; Dirección
                            </span>
                        </div>
                        <?= $this->Form->control('direccion',
                            [
                                'type'=> 'text',
                                'placeholder'=> 'Direccion',
                                'class' => 'form-control form-control-sm',
                                'autocomplete' => 'off',
                                'label'=>false]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-2">
                    <?= $this->Form->button("Registrar", ['class' => 'btn btn-sm btn-primary']) ?>
                    <?= $this->Form->control('map_lat',['type' => 'hidden']) ?>
                    <?= $this->Form->control('map_len',['type' => 'hidden']) ?>
                    <?= $this->Form->control('ubigeo',['type' => 'hidden']) ?>
                    <?= $this->Form->control('departamento',['type' => 'hidden']) ?>
                    <?= $this->Form->control('provincia',['type' => 'hidden']) ?>
                    <?= $this->Form->control('distrito',['type' => 'hidden']) ?>
                </div>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->Html->scriptBlock(" var ubicaciones = " . json_encode($ubicaciones));?>
