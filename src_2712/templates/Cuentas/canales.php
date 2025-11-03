<div class="">
    <div class="table-responsive gw-table">
        <table  class="table table-sm table-striped">
            <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id', ['label' => 'Código']) ?></th>
                    <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('created',['label' => 'F. Creación']) ?></th>
                    <th scope="col" class="actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cuentaCanalLlegadas as $cuentaCanalLlegada): ?>
                <tr>
                    <td><?= str_pad($cuentaCanalLlegada->id, 7, 0,0) ?></td>
                    <td><?= h($cuentaCanalLlegada->nombre) ?></td>
                    <td><?= h($cuentaCanalLlegada->created->format("d/m/Y")) ?></td>
                    <td class="actions">
                        <a href="javascript: void(0)" class="click_editar" data-id="<?= $cuentaCanalLlegada->id ?>"><i class="fas fa-pencil-alt fa-fw"></i> Editar</a>
                        <a href="javascript: void(0)" class="click_eliminar" data-id="<?= $cuentaCanalLlegada->id ?>"><i class="fas fa-trash fa-fw"></i> Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first(' «« Primero ') ?>
            <?= $this->Paginator->prev(' « Anterior ') ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(' Siguiente » ') ?>
            <?= $this->Paginator->last(' Último »» ') ?>
        </ul>
        <p><?= $this->Paginator->counter('Página {{page}} de {{pages}}, mostrando {{current}} registros de {{count}} en total, comenzando en {{start}}, terminando en {{end}}') ?></p>
    </div>
</div>
<?php
    echo $this->Element("form_cuenta_canal_llegada");
