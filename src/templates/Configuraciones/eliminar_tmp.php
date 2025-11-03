<div>
    <?= $this->Form->postLink("<i class='fa fa-trash fa-fw'></i> Eliminar los archivos temporales " , ['action' => 'del-tmp'], ['class' => 'btn btn-primary', 'escape' => false, 'confirm' => __("¿Está seguro que desea eliminar los archivos de la carpeta 'TMP'?")]) ?>
</div>