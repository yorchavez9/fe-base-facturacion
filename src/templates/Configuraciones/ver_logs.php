<div>
    <?php if($log_str == ''): ?>
        <a class='btn btn-primary' href="<?= $this->Url->build(['?' => [ 'show-log' => '1' ]]); ?>">
            <i class='fa fa-eye fa-fw'></i> Ver Logs
        </a>
        <?php else: ?>
            <a class='btn btn-primary' href="<?= $this->Url->build(['action' => 'ver-logs']); ?>">
                <i class='fa fa-eye-slash fa-fw'></i> Ocultar Logs
            </a>
    <?php endif; ?>
    <?= $this->Form->postLink("<i class='fa fa-trash fa-fw'></i> Eliminar Logs " , ['action' => 'del-logs'], ['class' => 'btn btn-primary', 'escape' => false, 'confirm' => __("¿Está seguro que desea eliminar los archivos de la carpeta Logs?")]) ?>
</div>
<hr>
<p>
    <?= $log_str ?>
</p>