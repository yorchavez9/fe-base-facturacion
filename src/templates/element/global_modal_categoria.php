<?php
/**
 * este modal lo utilizamos en la creacion de un producto, para
 * agregar una categoria al vuelo.
 *
 * tambien en el mantenimiento de categorias
 */

?>
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCategoria" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Información de la Categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= $this->Form->create(null, ['id' => 'modalCategoriaForm', 'enctype' => 'multipart/form-data']) ?>
            <div class="modal-body">
                <div class="row my-3">
                    <div class="col-md-12">
                        <label>Categoria</label>
                        <?= $this->Form->control('nombre',['label' => false, 'class' => 'form-control form-control-sm letras', 'placeholder' => 'Nombre de la Categoria', 'required' => true, 'autocomplete'=>'off']) ?>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-12">
                        <label>Descripción</label>
                        <?= $this->Form->control('descripcion',['label' => false, 'type'=> 'textarea', 'class' => 'form-control form-control-sm', 'placeholder' => 'Descripción de la Categoria']) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" value="" />
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
