<!-- <form id="formConsultar">
    <div class="row align-items-end">
        <div class="col-sm-6 col-md-6 col-lg-4 mb-2">
            <label>Filtrar por:</label>
            <select name="f_por" class="form-control form-control-sm">
                <option value="fecha_generacion" <?= ($f_por == 'fecha_generacion')? "selected":"" ?> >F. Generación</option>
                <option value="fecha_resumen"    <?= ($f_por == 'fecha_resumen')? "selected":"" ?> >F. Resumen</option>
            </select>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-2 mb-2">
            <label>F. Inicio:</label>
            <input type="text" name="f_inicio" class="form-control form-control-sm" value="<?=$f_inicio ?? ""?>" autocomplete="off">
        </div>

        <div class="col-sm-6 col-md-6 col-lg-2 mb-2">
            <label>F. Fin:</label>
            <input type="text" name="f_fin" class="form-control form-control-sm" value="<?=$f_fin ?? ""?>" autocomplete="off">
        </div>

        <div class="col-sm-6 col-md-6 col-lg-2 mb-2">
            <label>Estado</label>
            <?= $this->Form->Control("opt_estado", [
                'label' => false,
                'options' => ['ENVIADO' => 'ENVIADO', 'ACEPTADO' => 'ACEPTADO', 'ELIMINADO' => 'ELIMINADO', 'RECHAZADO' => 'RECHAZADO'],
                'empty' => '- Todos -',
                'value' => $opt_estado,
                'class' => 'form-control form-control-sm'
            ]) ?>
        </div>

        <div class="col-sm-12 col-lg-2 mb-2">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Consultar</button>
        </div>

    </div>
</form>
<br> -->

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" style="min-height: 45vh;">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                <th style="width: 150px;"> Fecha de emisión </th>
                    <th>Cliente </th>
                    <th style="width: 150px;">Documento</th>
                    <th style="width: 200px;">Totales</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="tabla_facturas">
                </tbody>
            </table>
            <nav aria-label="">
                <ul class="pagination justify-content-center" id="me_pagination">
                </ul>
            </nav>
        </div>
    </div>
</div>