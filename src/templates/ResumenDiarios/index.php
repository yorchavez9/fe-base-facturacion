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
        <div class="alert alert-info text-center">
            En el presente listado se muestran los Resúmenes Diario comunicados a Sunat
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive" style="min-height: 45vh;">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th> Nombre Único </th>
                    <th> F. Generación </th>
                    <th> F. Resumen </th>
                    <th> Ticket Sunat </th>
                    <th style="width: 200px;"> Estado </th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="tabla_resumenes">
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade"
     id="modalResumenDiario"
     tabindex="-1" role="dialog"
     aria-labelledby="modalNuevoResumenDiario"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formUsuariosLabel">Nuevo Resumen Diario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modalResumenDiarioForm" onsubmit="return Resumenes.generarResumen(this)">
                    <div class="row my-2">
                        <div class="col">
                            <label>Fecha de Geneneración</label>
                            <input type="text" name="fecha_generacion" class="form-control form-control-sm" autocomplete="off" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col text-right">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i> Guardar</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade"
     id="modalVerDescargar"
     tabindex="-1" role="dialog"
     aria-labelledby="modalVerDescargarLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVerDescargarLabel">Constancia de Recepción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="res_id">
                <div class="row">
                    <div class="col">
                        <table  class="table">
                            <tr>
                                <td  style="width: 300px"><b>Nombre Único</b></td>
                                <td id="nombre_unico"></td>
                            </tr>
                            <tr>
                                <td><b>Codigo de respuesta</b></td>
                                <td id="codigo_respuesta"></td>
                            </tr>
                            <tr>
                                <td><b>Descripción</b></td>
                                <td id="descripcion"></td>
                            </tr>
                            <tr>
                                <td><b>Notas</b></td>
                                <td id="notas"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-primary text-white" href="javascript:Resumenes.descargarCDR()"><i class="fa fa-fw fa-download"></i> Descargar CDR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

