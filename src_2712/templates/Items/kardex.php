<div class="row">
    <div class="col">
        <h6>Información del Producto  <button class="btn btn-primary btn-sm" onclick="modalExportar(<?= $item->id ?>)"> Exportar Kardex </button> </h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <tr>
                    <th>Nombre</th>
                    <td ><?= $item->nombre ?></td>
                    <th>Stock Global</th>
                    <td>
                        <?=$item->stock_global?>
                    </td>
                </tr>
                <tr>
                    <th>Descripcion</th>
                    <td colspan="3">
                        <?= $item->descripcion ?>
                    </td>
                </tr>
                <tr>
                    <th>Código</th>
                    <td>
                        <?= $item->codigo ?>
                    </td>
                    <th>Unidad</th>
                    <td>
                        <?= $item->unidad ?>
                    </td>
                </tr>
                <tr>
                    <th>P. Compra</th>
                    <td>
                        <?= $item->precio_compra ?>
                    </td>
                    <th>P. Venta</th>
                    <td>
                        <?= $item->precio_venta ?>
                    </td>
                </tr>
                <tr>
                    <th>Inc. IGV</th>
                    <td>
                        <?= $item->inc_igv == "1" ? "Si" : "No" ?>
                    </td>
                    <th>Es Visible</th>
                    <td>
                        <?= $item->es_visible == "1" ? "Si" : "No" ?>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>

<div class="stockHistorial index content">
    <div class="table-responsive gw-table">
        <table class="table table-sm table-striped table-hover">
            <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', '# Historial') ?></th>
                <th><?= $this->Paginator->sort('usuario_id') ?></th>
                <th><?= $this->Paginator->sort('operacion') ?></th>
                <th><?= $this->Paginator->sort('comentario') ?></th>
                <th><?= $this->Paginator->sort('doc_tipo',__('Tipo de Doc.')) ?></th>
                <th><?= $this->Paginator->sort('cantidad') ?></th>
                <th><?= $this->Paginator->sort('created', 'Fecha') ?></th>
                <!--<th class="actions">Acciones</th>-->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($stockHistorial as $stockHistorial): ?>
                <tr>
                    <td><?= str_pad($stockHistorial->id, 4, "0", 0) ?></td>
                    <td><?= $stockHistorial->has('usuario') ? $stockHistorial->usuario->nombre: '' ?></td>
                    <td><?= h($stockHistorial->operacion) ?></td>
                    <td><?= h($stockHistorial->comentario) ?></td>
                    <td><?= $stockHistorial->documento_tipo !== '' ? h($stockHistorial->documento_tipo): 'SIN ESPECIFICAR' ?>
                        <br>
                        <?= $stockHistorial->documento_relacionado !== '' ? h($stockHistorial->documento_relacionado): 'SIN ESPECIFICAR' ?>
                    </td>
                    <td><?= $this->Number->format($stockHistorial->cantidad) ?></td>
                    <td><?= h($stockHistorial->created->format('d/m/Y h:i A')) ?></td>
                   
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $this->element("paginador"); ?>
</div>

<?php echo $this->Element('modal_exportar_kardex'); ?>
