<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Nuevo Usuario</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Actualizar Contraseña</div>
            <div class="panel-body">
                <?= $this->Form->create(null, ['class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <label class="control-label col-md-2">Clave Anterior</label>
                    <div class="col-md-2">
                        <?= $this->Form->control('pswd_old', [ 'class' => 'form-control','label' => false]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Clave Nueva</label>
                    <div class="col-md-2">
                        <?= $this->Form->control('pswd_new', ['class' => 'form-control','label' => false]); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2 col-md-offset-2">
                        <?= $this->Form->button("Actualizar", ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
