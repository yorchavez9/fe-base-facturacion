<?= $this->Form->create($servicio, ['class' => '', 'id' => 'form_servicio']);
$this->Form->setTemplates(['inputContainer' => '{{content}}']);?>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label>Categorías</label>
                <div class="input-group" name="divcategoria">
                    <?= $this->Form->control('categoria_id',['label' => false, 'class' => 'form-control form-control-sm', 'options' => $categorias, 'empty' =>'--Seleccione--']); ?>
                    <span class="input-group-btn">
                <button class="btn btn-sm btn-primary" type="button" id="btnAgregarCategoria">
                    <i class="fas fa-plus fa-fw"></i>
                </button>
                </span>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-2">
                <?= $this->Form->control('inc_igv',[ 'label' => 'Precio Inc. IGV', 'options' => [0 => 'No Incluye', 1 => 'Incluye IGV'], 'class' => 'form-control form-control-sm']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4 col-lg-3 mb-2">
                        <label>Código</label>
                        <div class="input-group">
                            <?= $this->Form->control('codigo',['label' => false, 'class' => 'form-control form-control-sm', 'placeholder' => 'Código', 'maxlenght' => 16, 'id' => 'codigo', 'autocomplete' => 'off']) ?>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-9 mb-2">
                        <?= $this->Form->control('nombre',['required', 'class' => 'form-control form-control-sm', 'placeholder' => 'Nombre del Servicio', 'autocomplete' => 'off']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <?= $this->Form->control('descripcion',['class' => 'form-control form-control-sm', 'placeholder' => 'Decripción', 'rows'=>'3', 'autocomplete' => 'off']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4 mb-2">
                <?= $this->Form->control('precio_venta',[ 'class' => 'form-control form-control-sm', 'placeholder' => 'Precio Venta', 'required', 'autocomplete' => 'off', 'required']) ?>
            </div>
        </div>
        <div class="row mt-3">
            <div class="form-group">
                <div class="col-md-10 col-md-offset-2">
                    <input type="hidden" name="unidad" value="ZZ"/>
                    <input type="hidden" name="controlar_inventario" value="0"/>
                    <?= $this->Form->button("Guardar", ['class' => 'btn btn-primary btn-sm']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Form->end();
echo $this->Element("global_modal_categoria");?>

<div class="modal" tabindex="-1" id="productoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row justify-content-end">
                    <div class="col">
                        <label>Codigo: </label>
                        <input type="text" name="codigo_producto" class="form-control" autocomplete="off">
                    </div>

                    <div class="col">
                        <label>Precio de Producto: </label>
                        <input type="text" name="precio_producto" class="form-control" autocomplete="off">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label>Nombre del Producto: </label>
                        <input type="text" name="nombre_producto" class="form-control" autocomplete="off">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardarProducto()">Guardar</button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->scriptBlock('productos = ' . json_encode($productos)); ?>
