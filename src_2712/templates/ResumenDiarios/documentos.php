<div class="table-responsive">
    <table width="100%" class="table table-bordered" id="tabla_resumen">
        <tr>
            <td colspan="4" class="text-center font-weight-bold">
                <h5>RESUMEN DIARIO NRO  ########</h5>
            </td>
        </tr>
        <tr>
            <td>EMISOR</td>
            <td> --- </td>
            <td>RUC</td>
            <td> ########### </td>
        </tr>
        <tr>
            <td>NOMBRE COMERCIAL</td>
            <td> --- </td>
            <td>ESTADO</td>
            <td> --- </td>
        </tr>
        <tr>
            <td>F. GENERACIÓN</td>
            <td>--/--/----</td>
            <td>F. RESUMEN</td>
            <td>--/--/----</td>
        </tr>
    </table>
</div>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-boletas" role="tab" aria-controls="nav-home" aria-selected="true">
            Boletas
        </a>
        <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-notas" role="tab" aria-controls="nav-profile" aria-selected="false">
            Notas de Crédito/Débito
        </a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-boletas" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="py-2">
            <form action="" method="get" id="form_boletas">
                <div class="row">
                    <div class="col">
                        <label for=""> Filtre por número de documento: </label>
                        <input class="form-control" placeholder="Ej.: B001-00000010" name="opt_num_boleta" value="" />
                        <input type="hidden" name="opt_target" value="B">
                    </div>
                    <div class="d-flex align-items-end">
                        <button class="btn btn-primary"> <i class="fas fa-search"></i> </button>
                        &nbsp;
                        <button class="btn btn-danger" type="button" onclick="limpiarFiltroBoletas()"> <i class="fas fa-eraser"></i> </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive" style="min-height: 250px;">
            <table class="table table-sm table-striped table-hover">
                <thead class="text text-primary font-weight-bold">
                    <tr>
                        <th> ID </th>
                        <th style="min-width: 200px;">Emisor</th>
                        <th style="min-width: 200px;">Cliente</th>
                        <th style="min-width: 200px;">Documento</th>
                        <th class="text-center" style="min-width: 200px;">Totales</th>
                        <th class="actions">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla_boletas">
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-notas" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="py-2">
            <form action="" method="get" id="form_notas">
                <div class="row">
                    <div class="col">
                        <label for=""> Filtre por número de documento: </label>
                        <input class="form-control" placeholder="Ej.: FC01-00000150" name="opt_num_nota" value="" />
                        <input type="hidden" name="opt_target" value="N">
                    </div>
                    <div class="d-flex align-items-end">
                        <button class="btn btn-primary"> <i class="fas fa-search"></i> </button>
                        &nbsp;
                        <button class="btn btn-danger" type="button" onclick="limpiarFiltroNotas()"> <i class="fas fa-eraser"></i> </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive" style="min-height: 250px;">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th> ID </th>
                        <th style="min-width: 200px;">Emisor</th>
                        <th style="min-width: 200px;">Cliente</th>
                        <th style="min-width: 200px;">Documento</th>
                        <th class="text-center" style="min-width: 200px;">Totales</th>
                        <th class="actions">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla_notas">
                </tbody>
            </table>
        </div>
    </div>
<?php
echo $this->Html->ScriptBlock("var resumen_id = '" . $resumen_id . "';");
