<form class="form">
    <?php
    $this->Form->setTemplates(['inputContainer' => '{{content}}'])
    ?>
    <div class="row">
        <div class="col-12 col-sm">
            <div class="form-group">
                <label class="control-label">Periodo Tributario</label>
                <div class="form-group d-flex">
                    <div>
                        <input class="form-control form-control-sm text-center" autocomplete="off" name="periodo" value="<?= $periodo ?>">
                    </div>
                    <div class="">
                        <button class="btn btn-outline-primary btn-sm btn-search" type="submit" id=""> <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-3" style="max-width:300px">
            <label class="control-label">&nbsp;</label><br />
            <div class="input-group">
                <button type="submit" name="exportar" value="1" class="btn btn-sm btn-outline-success mx-1 w-100">
                    <i class="fa fa-fw fa-file-excel"></i> Exportar Consolidado
                </button>
            </div>
        </div>
    </div>

</form>
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-compras" role="tab" aria-controls="nav-home" aria-selected="true">
            Compras
        </a>
        <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-ventas" role="tab" aria-controls="nav-profile" aria-selected="false">
            Ventas
        </a>
        <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-notas-credito" role="tab" aria-controls="nav-profile" aria-selected="false">
            Notas de Crédito
        </a>
        <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-notas-debito" role="tab" aria-controls="nav-profile" aria-selected="false">
            Notas de Débito
        </a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-compras" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="row">
            <div class="col-12 pb-2">
                <BUTTON class="btn btn-sm btn-outline-success" id="exportar_compras">
                    <i class="fa fa-fw fa-file-excel"></i> Reporte
                </BUTTON>
            </div>
        </div>
        <?= $this->Element("reportes/listado_compras",['compras'    =>  $compras]) ?>
    </div>
    <div class="tab-pane fade" id="nav-ventas" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="row">
            <div class="col-12 pb-2">
                <BUTTON class="btn btn-sm btn-outline-success" id="exportar_ventas">
                    <i class="fa fa-fw fa-file-excel"></i> Reporte
                </BUTTON>
            </div>
        </div>
        <?= $this->Element("reportes/listado_ventas" , ['ventas'   =>  $ventas]) ?>
    </div>
    <div class="tab-pane fade" id="nav-notas-credito" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="row">
            <div class="col-12 pb-2">
                <BUTTON class="btn btn-sm btn-outline-success" id="exportar_notas_credito">
                    <i class="fa fa-fw fa-file-excel"></i> Reporte
                </BUTTON>
            </div>
        </div>
        <?= $this->Element("reportes/listado_notas_credito" , ['sunatFeNotas'   =>  $notas_credito]) ?>
    </div>
    <div class="tab-pane fade" id="nav-notas-debito" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="row">
            <div class="col-12 pb-2">
                <BUTTON class="btn btn-sm btn-outline-success" id="exportar_notas_debito">
                    <i class="fa fa-fw fa-file-excel"></i> Reporte
                </BUTTON>
            </div>
        </div>
        <?= $this->Element("reportes/listado_notas_debito" , ['sunatFeNotas'   =>  $notas_debito]) ?>
    </div>
</div>