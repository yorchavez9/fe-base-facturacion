<?= $this->Form->create($producto, ['class' => '', 'enctype' => 'multipart/form-data']);
$this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
<div class="form-row">
    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <label>Categorías</label>
        <div class="input-group" name="divcategoria">
            <?= $this->Form->control('categoria_id', ['label' => false, 'class' => 'form-control form-control-sm', 'options' => $categorias, 'empty' => '--Seleccione--']); ?>
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm" type="button" id="btnAgregarCategoria">
                    <i class="fas fa-plus fa-fw"></i>
                </button>
            </span>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <label>Marcas</label>
        <div class="input-group" name="divmarca">
            <?= $this->Form->control('marca_id', ['label' => false, 'class' => 'form-control form-control-sm', 'options' => $marcas, 'empty' => '--Seleccione--']); ?>
            <span class="input-group-btn">
                <button class="btn btn-sm btn-primary" type="button" id="btnAgregarMarca">
                    <span class="glyphicon glyphicon-search" aria-hidden="true">
                    </span>
                    <i class="fas fa-plus"></i>
                </button>
            </span>
        </div>
    </div>
    <div class="col mb-3">
        <label for="item_ubicacion">Ubicación</label>
        <?=
        $this->Form->text(
            'ubicacion',
            [
                'class' =>  'form-control form-control-sm',
                'autocomplete'  =>  'off',
                'placeholder'   =>  'Ubicación de su producto',
                'id'            =>  'item_ubicacion'
            ]
        )
        ?>
    </div>
</div>
<div class="form-row">
    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <?= $this->Form->control('controlar_inventario', ['label' => '¿Controlar Inventario?', 'options' => ['0' => 'No Controlar', '1' => 'Si Controlar'],  'class' => 'form-control form-control-sm']) ?>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
        <label>Código</label>
        <div class="input-group">
            <?= $this->Form->control('codigo', ['label' => false, 'class' => 'form-control form-control-sm', 'placeholder' => 'Código', 'maxlenght' => 16, 'autocomplete' => 'off']) ?>
            <div class="input-group-append">
                <a href="javascript:generarCodigo()" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-code"></i></a>
            </div>
        </div>

    </div>
    <div class="col-sm-12 col-md-6 mb-3">
        <?= $this->Form->control('nombre', ['class' => 'form-control form-control-sm', 'placeholder' => 'Nombre del Producto', 'autocomplete' => 'off']) ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-lg-6 mb-3">
        <?= $this->Form->control('descripcion', ['class' => 'form-control form-control-sm', 'placeholder' => 'Decripción', 'rows' => '3', 'autocomplete' => 'off', 'value' => '']) ?>
    </div>
    <div class="col-sm-12 col-lg-6 mb-3">
        <?=
        $this->Form->control(
            'descripcion_alternativa',
            [
                'id'            =>  'descripcion_alternativa',
                'label' =>  'Descripción alternativa para búsquedas',
                'placeholder'   =>  'Descripción alternativa',
                'class'         =>  'form-control form-control-sm',
                'autocomplete'  =>  'off',
                'rows'          =>  '3'
            ]
        )
        ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-lg-2 mb-3">
        <label>Precio Compra</label>
        <div class="input-group input-group-sm">
            <?= $this->Form->control('precio_compra', ['label' => false, 'class' => 'form-control form-control-sm', 'placeholder' => 'P. Venta Mínimo', 'autocomplete' => 'off']) ?>
        </div>
    </div>
    <div class="col-sm-6 col-lg-2 mb-3">
        <label>Precio Venta</label>
        <div class="input-group input-group-sm">
            <?= $this->Form->control('precio_venta', ['label' => false, 'class' => 'form-control form-control-sm', 'placeholder' => 'Precio Venta', 'autocomplete' => 'off']) ?>
        </div>
    </div>
    <div class="col-sm-6 col-lg-2 mb-3">
        <?= $this->Form->control('tipo_moneda', ['label' => 'Tipo de Moneda', 'options' => $tipo_monedas, 'class' => 'form-control form-control-sm', 'placeholder' => 'Tipo de Moneda', 'value' => 'PEN']) ?>
    </div>
    <div class="col-sm-6 col-lg-2 mb-3">
        <?= $this->Form->control('inc_igv', ['label' => 'Precio Inc. IGV', 'options' => [0 => 'No Incluye', 1 => 'Incluye IGV'], 'class' => 'form-control form-control-sm', 'placeholder' => 'P. Inc IGV']) ?>
    </div>
    <div class="col-sm-6 col-lg mb-3">
        <?= $this->Form->control('stock_minimo_local', ['class' => 'form-control form-control-sm', 'placeholder' => 'P. Venta Mayoreo', 'label' => 'Alarma de Stock Min.', 'autocomplete' => 'off']) ?>
    </div>
</div>

<div class="row align-items-end">
    <div class="col mb-3">
        <label>Cargar Imagen:</label>
        <input type="file" accept="image/*" name="file_img_ruta" class="form-control form-control-sm">
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <button class="btn btn-primary" type="submit">
            <i class="fas fa-save fa-fw"></i> Guardar
        </button>
    </div>
</div>
<?php
echo $this->Form->end();
echo $this->Element("global_modal_marca");
echo $this->Element("global_modal_categoria");
