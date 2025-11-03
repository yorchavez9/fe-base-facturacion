<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" style="min-height: 45vh">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th style="width: 150px;"><?= $this->Paginator->sort('fecha_venta', ['label' => 'Fecha / Hora']) ?></th>
                        <th><?= $this->Paginator->sort('cliente_id', ['label' => 'Cliente']) ?></th>
                        <th style="width: 150px;"><?= $this->Paginator->sort('documento_tipo', ['label' => 'Documento']) ?></th>
                        <th style="width: 200px;" colspan="2">Totales</th>
                        <th>Enviar a Sunat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $v) :

                        echo $this->Element('global_fila_ventas', ['v' => $v, 'show_actions'    =>  false]);

                    endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="paginator text-center">
            <?= $this->element("paginador"); ?>
        </div>
    </div>
</div>