
<div class="modal" tabindex="-1" role="dialog" id="modalEvento">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Registro de Evento <span id="modalEventoTitulo"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                    <?php
                        echo $this->Form->create(null, ['class' => 'form']);
                        $this->Form->setTemplates(['inputContainer' => '{{content}}']);

                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= $this->Form->control('contenido', [ 'label' => false, 'type' => 'textarea', 'class' => 'form-control','id' => 'modalEventoContenido']);?>
                            </div>
                        </div>
                    </div>
                    <?= $this->Form->control('cliente_id', ['type' => 'hidden']);?>
                    <?= $this->Form->control('proyecto_id', ['type' => 'hidden']);?>
                    <?= $this->Form->control('cotizacion_id', ['type' => 'hidden']);?>
                    <?= $this->Form->control('recargar', ['type' => 'hidden']);?>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= $this->Form->control('proximo_evento', ['label' => 'Recordatorio', 'type' => 'text', 'class' => 'form-control']);?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Fecha</label>
                                <input type="text" class="form-control" name="fecha_proximo" value="<?= date("Y-m-d") ?>" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Hora</label>
                                <input type="text" class="form-control" name="hora_proximo" value="<?= date("H:i:s", strtotime(" +3 Hour")) ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                            <label>Calificación</label>
                            <select name="calificacion" class="form-control">
                                <option value="1"> 1 &#9733;</option>
                                <option value="2"> 2 &#9733;</option>
                                <option value="3"> 3 &#9733;</option>
                                <option value="4"> 4 &#9733;</option>
                                <option value="5"> 5 &#9733;</option>
                            </select>
              


                            
                            </div>
                        </div>
                    </div>
                <?= $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalEventoBtnGuardar" class="btn btn-primary"><i class="fa fa-save"></i>   Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
        
    </div>
</div>