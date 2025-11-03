<?= $this->Form->create(null, ['id' => 'formNuevaGuia', 'name' => 'formNuevaGuia']) ?>
<div class="row">
    <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
        <label>Cargar Items</label>
        <button type="button" onclick="GuiaremRemitenteAdd.abrirModalDesdeVenta()" class="btn btn-outline-primary btn-sm btn-block"><i class="fas fa-exchange-alt fa-fw"></i> Cargar desde Venta</button>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-2 mb-2">
        <label>Serie</label>
        <select name="serie" id="serie" class="form-control form-control-sm">
            
        </select>
        <input type="text" hidden name="correlativo" placeholder="Correlativo" maxlength="8" autocomplete="off">
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
        <label>Establecimiento</label>
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-store fa-fw"></i></div>
            </div>
            <input type="hidden" name="establecimiento_id" value="<?php // $establecimiento->id ?>">
            <input type="hidden" name="establecimiento_codigo" value="<?php // $establecimiento->codigo ?>">
            <input type="hidden" name="establecimiento_ubigeo" value="<?php // $establecimiento->ubigeo ?>">
            <input type="hidden" name="establecimiento_departamento" value="<?php // $establecimiento->departamento ?>">
            <input type="hidden" name="establecimiento_provincia" value="<?php // $establecimiento->provincia ?>">
            <input type="hidden" name="establecimiento_distrito" value="<?php // $establecimiento->distrito ?>">
            <input type="hidden" name="establecimiento_urbanizacion" value="<?php // $establecimiento->urbanizacion ?>">
            <input type="hidden" name="establecimiento_direccion" value="<?php // $establecimiento->direccion ?>">
            <input class="form-control" type="text" name="establecimiento" readonly value="<?php // $establecimiento->nombre ?>" />
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-2 mb-2">
        <label>F. Emisión</label>
        <input type="text" name="fecha_emision" class="form-control form-control-sm" value="<?=date('Y-m-d') ?>" autocomplete="off" readonly >
    </div>
    <div class="col-sm-12 col-md-6 col-lg-2 mb-2">
        <label>F. Traslado</label>
        <input type="text" name="fecha_traslado" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" autocomplete="off">
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-ban fa-fw"></i>
                </span>
            </div>
            <?=
                $this->Form->control('anular_guia_codigo', [
                    'label'         =>  false,
                    'class'         =>  'form-control form-control-sm',
                    'id'            =>  'anular_guia_codigo',
                    'placeholder'   =>  'Ejem. V003-00000001',
                    'readonly'      =>  true
                ])
            ?>
            <div class="input-group-append">
                <button class="btn btn-sm btn-outline-primary" type="button" onclick="GuiaremRemitenteAdd.showCancelingModal()">
                    <i class="fa fa-search fa-fw"></i>
                </button>
            </div>
            <div class="input-group-append" id="divCleanCanceling">
                <button type="button" onclick="GuiaremRemitenteAdd.cleanCancelingFields()" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-eraser fa-fw"></i></button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-user-check fa-fw"></i>
                </span>
            </div>
            <input type="text" class="form-control" readonly name="cliente_num_doc" value="" />
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-building fa-fw"></i>
                </span>
            </div>
            <input type="hidden" name="cliente_id" class="form-control" autocomplete="off" placeholder="Cliente">
            <input type="hidden" name="cliente_tipo_doc" class="form-control" autocomplete="off" placeholder="Cliente">
            <input type="text" name="cliente_razon_social" class="form-control" autocomplete="off" placeholder="Cliente" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-6 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    Modalidad de Traslado
                </span>
            </div>
            <select class="form-control" name="modo_traslado" required onchange="GuiaremRemitenteAdd.ModoTrasladoChange(this)">
                <option value="">-- Modo de Traslado --</option>
                <option value="01">Transporte Público</option>
            </select>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 mb-2">
        
    </div>
</div>
<div class="text-right" id="checkTrasnPrivado" style="display: none">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" id="vehiculo_menor" name="transp_vehiculo_m1_l1" onchange="GuiaremRemitenteAdd.cambioVehiculoMenor()">
        <label class="form-check-label" for="vehiculo_menor">
            Vehiculo menor M1 ó L1
        </label>
    </div>
</div>
<div id="guiaremTrans" style="display: none">
    <div class="row mb-2">
        <div class="col-11" id="transTipo">
        </div>
    </div>
    <div id="datosTrans" class="mb-3 mb-sm-2">
    </div>
    <div id="addConductor" style="display: none" >
        <a href="javascript:void(0)" onclick="GuiaremRemitenteAdd.agregarConductorPrivado()">Agregar Conductor</a>
    </div>
</div>
<div id="guiaremVehiculos" class="pt-3" style="display: none">
    <div class="row mb-2">
        <div class="col-md-3" id="transTipo">
        </div>
    </div>
    <div id="datosVehiculos" >
    </div>
    <div>
        <a href="javascript:void(0)" onclick="GuiaremRemitenteAdd.agregarVehiculoPrivado()">Agregar Vehículo</a>
    </div>
    <br />
</div>
<div class="row my-3" >
    <div class="col-12 mb-1">
        Documentos relacionados (obligatorio):
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-sticky-note fa-fw"></i> &nbsp; Tipo Doc.
                </span>
            </div>
            <select name="doc_adicional_tipo" class="form-control" required>
                <option value=""> -Seleccione- </option>
                <option value="01"> Factura </option>
                <option value="03"> Boleta </option>
            </select>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-2 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-id-card fa-fw"></i> &nbsp; Serie
                </span>
            </div>
            <input type="text" name="doc_adicional_serie" required class="form-control" placeholder="Serie" autocomplete="off" >
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-sticky-note fa-fw"></i> &nbsp; Correlativo
                </span>
            </div>
            <input type="text" name="doc_adicional_correlativo" required class="form-control" placeholder="Correlativo" autocomplete="off" >
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-building fa-fw"></i> &nbsp; RUC Remitente
                </span>
            </div>
            <input type="text" name="doc_adicional_emisor" required class="form-control" placeholder="RUC Remitente" autocomplete="off" >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-1">
        Detalles de la guia:
    </div>
    <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" style="width: 90px;">
                    <i class="fas fa-weight fa-fw"></i> &nbsp; Peso
                </span>
            </div>
            <input class="form-control" type="hidden" name="peso_unidad" value="KGM">
            <input class="form-control" type="number" name="peso_total" step="any" min="0.01" value="0.01" required>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 col-lg-9 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-sticky-note fa-fw"></i> &nbsp; Observaciones
                </span>
            </div>
            <input type="text" name="observaciones" class="form-control" placeholder="Observaciones" autocomplete="off">
        </div>
    </div>
</div>
<div class="row"  id="direccionPartida">
    <div class="col-sm-12 col-md-6 col-lg-5 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" style="width: 90px;">
                    <i class="fas fa-home fa-fw"></i> &nbsp; Partida
                </span>
            </div>
            <input type="hidden" name="partida_ubigeo" class="class_partida" value="<?php // $establecimiento->ubigeo ?>" />
            <input type="text" name="partida_ubigeo_full" class="form-control form-control-sm class_partida" placeholder="Distrito / Provincia / Región" value="<?php // $establecimiento->distrito ?> / <?php // $establecimiento->provincia ?> / <?php // $establecimiento->departamento ?>" required />
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-7 mb-2">
        <input type="text" name="partida_direccion" class="form-control form-control-sm class_partida" placeholder="Dirección de Partida" autocomplete="off" value="<?php // $establecimiento->urbanizacion . " " . $establecimiento->direccion ?>" required>
    </div>
</div>

<div class="row" id="direccionLlegada">
    <div class="col-sm-12 col-md-6 col-lg-5 mb-2">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text" style="width: 90px;">
                    <i class="fas fa-truck-moving fa-fw"></i> &nbsp; Llegada
                </span>
            </div>
            <input type="hidden" name="llegada_ubigeo" class="class_partida" />
            <input type="text" name="llegada_ubigeo_full" class="form-control form-control-sm class_partida" placeholder="Distrito / Provincia / Región" required />
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-7 mb-2">
        <input type="text" name="llegada_direccion" class="form-control form-control-sm class_partida" placeholder="Dirección de Llegada" autocomplete="off" value="" required>
    </div>
</div>

<div class="row align-items-end">
    <div class="col">
        <label>Descripción Motivo traslado:</label>
        <textarea name="descripcion_motivo_traslado" class="form-control" placeholder="Descripción Motivo"></textarea>
    </div>


</div>

<div class="row mt-2">
    <div class="col-md-6 text-right">
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" >Guia a Anular</span>
            </div>
            <input type="text" name="anular_guia_codigo" class="form-control" placeholder="Serie y Correlativo" >
        </div>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-sm btn-outline-primary " href="javascript:FormGuiaRemRemProductos.Nuevo()"><i class="fa fa-fw fa-plus"></i>Agregar Producto</a>
        <button type="submit" class="btn btn-sm btn-primary" id="btnGuiaRemSubmit"><i class="fa fa-save fa-fw"></i> Guardar</button>
    </div>
</div>
<br>

<table class="table table-responsive-sm">
    <tr class="text text-primary font-weight-bold">
        <td>Código</td>
        <td>Producto</td>
        <td>Unidad de Medida</td>
        <td>Cantidad</td>
        <td class="actions">Acciones</td>
    </tr>
    <tbody id="tabla_items">

    </tbody>
</table>

<br>

<input type="hidden" name="registros">

<?= $this->Form->end() ?>

<div class="modal fade" id="guiaRemModalFromVenta" tabindex="-1" role="dialog" aria-labelledby="guiaRemModalFromVentaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label>Cargar Productos desde Factura o Boleta</label>
                <div class="mb-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-file fa-fw"></i>
                            </span>
                        </div>
                        <input type="text" onkeyup="mayus(this);" class="form-control" name="modal_venta" placeholder="Ejemplo: F001-00000001" autocomplete="off" />
                        <div class="input-group-append">
                            <button type="button" onclick="GuiaremRemitenteAdd.fetchVenta('factura')" class="btn btn-sm btn-outline-primary"><i class="fas fa-check fa-fw"></i></button>
                        </div>
                    </div>
                </div>

                <label>Cargar Productos desde Nota de Venta</label>
                <div class="mb-3">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-file fa-fw"></i>
                            </span>
                        </div>
                        <input type="text" onkeyup="mayus(this);" class="form-control" name="modal_nventa" placeholder="Ejemplo: NV-00000001" autocomplete="off" />
                        <div class="input-group-append">
                            <button type="button" onclick="GuiaremRemitenteAdd.fetchVenta('notaventa')" class="btn btn-sm btn-outline-primary"><i class="fas fa-check fa-fw"></i></button>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-sm btn-outline-primary " data-dismiss="modal">
                        <i class="fas fa-times fa-fw"></i> Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="guiaRemAnularGuia" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <label>Seleccionar Guia a Anular</label>
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-file fa-fw"></i>
                                </span>
                            </div>
                            <input type="text"  class="form-control" id="guiarem_codigo_anular" name="guiarem_codigo_anular" maxlength="13" placeholder="Ejemplo: T001-00000001"  autocomplete="off" />
                            <div class="input-group-append">
                                <button type="button" onclick="GuiaremRemitenteAdd.fetchGuideForCanceling()" class="btn btn-sm btn-outline-primary"><i class="fas fa-check fa-fw"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-outline-primary btn-block" data-dismiss="modal">
                            <i class="fas fa-times fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #divCleanCanceling{
        display: none;
    }
</style>
<?php
echo $this->Html->scriptBlock('var listado_items = ' . json_encode($items) );
echo $this->Html->ScriptBlock("var establecimiento_default = '{$establecimiento_default}' ;" );

echo $this->Element('form_guia_rem_remitentes_productos'); ?>
