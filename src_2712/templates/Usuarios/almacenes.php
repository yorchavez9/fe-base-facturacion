<div class="index content">
    <div class="alert alert-info">
        A través de esta pantalla podrá asociar almacenes al usuario <b><?= $usuarioObj->nombre ?></b>
    </div>
    <div class="text-center">
        <h5><?= $usuarioObj->nombre?></h5>
        <h6>Almacenes asociados </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover">

            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('nombre') ?></th>
                    <th>Ciudad</th>
                    <th><?= $this->Paginator->sort('direccion', ['label' => 'Direccion']) ?></th>
                    <th class="actions"><?= __('Acciones') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($almacenes as $alma): ?>
                <tr>
                    <td>
                        <?= $alma->nombre ?><br/>
                        <small><?= str_pad($alma->id,7,'0',0)?></small>
                    </td>
                    <td>
                        <?= $alma->departamento ?><br/>
                        <?= $alma->provincia ?>
                    </td>
                    <td>
                        <?= $alma->direccion ?><br/>
                        <?= $alma->distrito ?>
                    </td>
                    <td class="actions">
                        <label>
                            <input type="checkbox" name="almacenes[<?=$alma->id?>]" form="formAlmacenes" <?php echo ( $alma->seleccionado) ? 'checked':''?> />
                            Permitido
                        </label>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <form id="formAlmacenes" method="POST">
        <button type="submit" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-save fa-fw"></i>
            Guardar
        </button>
    </form>
</div>
