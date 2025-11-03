<?php
echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']);
$this->Form->setTemplates(['inputContainer' => '{{content}}']);
?>
    <div>
        <fieldset>
            <legend> Planes </legend>
            <div class="row ">
                <div class="col form-group">
                    <label class="control-label">Fecha de Inicio</label>
                    <?= $this->Form->control("fe_fecha_inicio", ['label' => false, 'class' => 'form-control fecha', 'value' => $vars['fe_fecha_inicio'] ? $vars['fe_fecha_inicio'] : '' ]) ?>
                </div>
                <div class="col form-group">
                    <label class="control-label">Plan</label>
                    <?= $this->Form->control("fe_plan", ['label' => false, 'class' => 'form-control', 'options' => ['LITE' => 'Facturación Electrónica', 'FULL' => 'Facturación Electrónica + Control de Inventarios'], 'value' => $vars['fe_plan']]) ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">Periodo de prueba</label>
                <?= $this->Form->control("fe_periodo_prueba", ['label' => false, 'class' => 'form-control', 'options' => ['0' => 'Deshabilitado', '1' => 'Activo'], 'value' => $vars['fe_periodo_prueba']]) ?>
            </div>
            <div class="form-group">
                <label class="control-label">Renovación</label>
                <?= $this->Form->control("fe_renovacion", ['label' => false, 'class' => 'form-control', 'options' => ['MENSUAL' => 'Mensual', 'ANUAL' => 'Anual', 'LIFETIME' => 'Lifetime'], 'value' => $vars['fe_renovacion']]) ?>
            </div>
            <div class="row ">
                <!-- <div class="col form-group" id='div_dia_renovacion'>
                    <label class="control-label">Dia renovación</label>
                    <?= $this->Form->control("fe_dia_renovacion", ['label' => false, 'class' => 'form-control', 'options' => [] , 'value' => $vars['fe_dia_renovacion'] , 'empty' => '' ]) ?>
                </div>
                <div class="col form-group" id='div_mes_renovacion'>
                    <label class="control-label">Mes renovación</label>
                    <?= $this->Form->control("fe_mes_renovacion", ['label' => false, 'class' => 'form-control', 'options' => $optMes, 'value' => $vars['fe_dia_renovacion'], 'empty' => '']) ?>
                </div> -->
                <div class="col form-group" id='div_periodo_gracia'>
                    <label class="control-label">Periodo de gracia </label>
                    <?= $this->Form->control("fe_periodo_gracia", ['label' => false, 'class' => 'form-control', 'value' => $vars['fe_periodo_gracia'], 'type' => 'number' ,'min' => '0' ] ) ?>
                </div>
            </div>
        </fieldset>
    </div>
    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-save"></i> Guardar</button>

<?php echo $this->Form->end(); ?>