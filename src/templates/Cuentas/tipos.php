<div class="">
    <div class="table-responsive gw-table">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id', ['label' => 'Código']) ?></th>
                <th scope="col"><?= $this->Paginator->sort('nombre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('descripcion') ?></th>

                <th scope="col" class="actions">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= str_pad($item->id, 7, 0,0) ?></td>
                    <td><?= h($item->nombre) ?></td>
                    <td><?= h($item->descripcion) ?></td>

                    <td class="actions">
                        <a href="javascript: void(0)" class="click_editar" data-id="<?= $item->id ?>"><i class="fas fa-pencil-alt fa-fw"></i> Editar</a>
                        <a href="javascript: void(0)" class="click_eliminar" data-id="<?= $item->id ?>"><i class="fas fa-trash fa-fw"></i> Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element("paginador"); ?>
</div>
<?php
echo $this->Element("form_cuenta_tipo");
