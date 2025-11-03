<?php
echo $this->Form->create(null, ['id' => 'formNuevaVenta', 'onsubmit'   =>  'nuevaCotizacion.guardar(event)']);
$this->Form->setTemplates(['inputContainer' => '{{content}}'])
?>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="input-group my-2">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-user fa-fw"></i>
                </span>
            </div>
            <input type="text" name="cliente_ruc" maxlength="11" class="form-control form-control-sm" placeholder="RUC" aria-describedby="button-addon2" id="clienteTipoDocInput" autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-sm btn-outline-primary estado_busqueda" type="button" id="clienteEstadoBusqueda1" onclick="nuevaCotizacion.consultaDniRucAjax()">
                    <i class="fas fa-exchange-alt fa-fw"></i>
                </button>
                <button class="btn btn-sm btn-outline-primary estado_busqueda2 " title="Guardar" style="display: none;" type="button" id="clienteEstadoBusqueda2" onclick="nuevaCotizacion.guardarCliente()">
                    <i class="fas fa-save fa-fw"></i>
                </button>
                <button class="btn btn-sm btn-outline-primary estado_busqueda2 " title="Guardar" style="display: none;" type="button" id="clienteEstadoBusqueda3">
                    <i class="fas fa-check fa-fw"></i>
                </button>
            </div>
            <div class="input-group-append">
                <button class="btn btn-sm btn-outline-primary" type="button" id="button-addon3" onclick="nuevaCotizacion.clienteLimpiarDatos()">
                    <i class="fas fa-eraser fa-fw"></i>
                </button>
            </div>
        </div>

        <div class="input-group my-2">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-user fa-fw"></i>
                </span>
            </div>
            <input type="text" name="cliente_razon_social" placeholder="Razón Social" class="form-control form-control-sm" value="" autocomplete="off" />
        </div>

        <div class="input-group my-2">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-map-marker fa-fw"></i>
                </span>
            </div>
            <input type="text" name="cliente_domicilio_fiscal" placeholder="Dirección" class="form-control form-control-sm" value="" autocomplete="off" />

            <input type="hidden" name="cliente_id" value="" />
            <input type="hidden" name="cliente_doc_tipo" value="" />
            <input type="hidden" name="cliente_doc_numero" value="" />
            <input type="hidden" name="cliente_persona_tipo" value="" />

        </div>

    </div>

    <div class="col-12 col-md-6">

        <div class="row my-2">
            <div class="col-6">
                <a href="javascript:nuevaCotizacion.showModalCoti()" class="btn btn-primary btn-sm w-100">Cargar Items</a>
            </div>
            <div class="col-6">
                <select name="tipo_moneda" class="form-control form-control-sm">
                    <option value="PEN">Soles</option>
                    <option value="USD">Dólares</option>
                </select>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-6 pr-1">
                <input type="text" name="fecha_venta" class="form-control form-control-sm text-center" value="<?= date("Y-m-d") ?>" autocomplete="off" />
            </div>
            <div class="col-6 pl-1">
                <input type="text" name="hora_venta" class="form-control form-control-sm text-center hora_venta" readonly="true" autocomplete="off" />
            </div>
        </div>
        <div class="row my-2">
            <div class="col-7 pr-1 text-right mt-2">
                Validez de la oferta:
            </div>
            <div class="col-3">
                <input type="number" min="1" max="100"  class="form-control form-control-sm dias-number" name="dias_vencimiento" value="1">
            </div>
            <div class="col-2 text-left mt-2">
                dias.
            </div>
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="d-none d-sm-block col-3">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-barcode fa-fw"></i>
                </span>
            </div>
            <input class="form-control form-control-sm ingresocodigo" name="codigo" placeholder="Código + Tecla Enter" autocomplete="off" />
        </div>
    </div>
    <div class="col-12 col-sm-9">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-keyboard fa-fw"></i>
                </span>
            </div>
            <input id="campoBusqueda1" type="text" name="nombre_producto" class="form-control form-control-sm ingresoproducto " placeholder="Busque por Marca / Categoría / Nombre" autocomplete="off">
            <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-primary" id="basic-addon2" onclick="nuevaCotizacion.abrirBuscadorProductos()">
                    <i class="d-block d-md-none fas fa-search fa-fw"></i> <span class="d-none d-md-block"><i class="fas fa-search fa-fw"></i> Buscar</span>
                </button>
            </div>
            <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-success" onclick="FormItems.Nuevo()">
                    <i class="fas fa-plus fa-fw fa-fw d-block d-sm-none"></i> <span class="d-none d-sm-block"><i class="fas fa-plus fa-fw fa-fw"></i> Producto</span>
                </button>
            </div>
            <div class="input-group-append">
                <a href="javascript:nuevaCotizacion.abrirModalServicio()" class="btn btn-sm btn-primary bg-info border-info w-100"> <i class="fas fa-plus fa-fw"></i> Servicio</a>
            </div>
        </div>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-12">
        <div class="row justify-content-between">
            <div class="col-md-2 align-middle">
                <h6>Detalle la Proforma</h6>
            </div>
            <div class="col-md-10">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th style="width: 25px;">#</th>
                        <th style="width: 100px;">Código</th>
                        <th>Producto</th>
                        <th style="width: 80px;">Cantidad</th>
                        <th style="width: 100px;">Unidad</th>
                        <th style="width: 100px;">P. Unit</th>
                        <th style="width: 100px;">P. Total</th>
                        <th style=""></th>
                        <th style=""></th>
                    </tr>
                </thead>
                <tbody id="detalle">

                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <fieldset>
            <legend>Comentarios Adicionales</legend>
            <textarea class="form-control form-control-sm" name="comentarios" placeholder="Comentarios Adicionales"></textarea>
        </fieldset>
    </div>
</div>

<div class="row">

    <div class="col-12 offset-0 col-md-6 offset-md-6">
        <fieldset>

            <div class="row">
                <div class="col">
                    <legend>Resumen</legend>
                </div>

            </div>
            <!-- <div class="form-group">

                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <span style="width: 150px;height: 83%;" class="input-group-text">Op. Gravadas</span>
                        </div>
                        <input type="number" readonly name="mto_oper_gravadas" class="form-control form-control-sm text-right">
                    </div>
                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <span style="width: 150px;height: 83%;" class="input-group-text">Op. Exoneradas</span>
                        </div>
                        <input type="number" readonly name="mto_oper_exoneradas" class="form-control form-control-sm text-right">
                    </div>
                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <span style="width: 150px;height: 83%;" class="input-group-text">Op. Inafectas</span>
                        </div>
                        <input type="number" readonly name="mto_oper_inafectas" class="form-control form-control-sm text-right">
                    </div>
                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <span style="width: 150px;height: 83%;" class="input-group-text">Monto IGV</span>
                        </div>
                        <input type="number" readonly name="mto_coti_igv" class="form-control form-control-sm text-right">
                    </div>
                </div> -->

            <div class="form-group">
                <div class="input-group my-2" id="div_op_gravadas">
                    <div class="input-group-prepend">
                        <span style="width: 150px;height: 83%;" class="input-group-text">Op. Gravadas</span>
                    </div>
                    <input type="text" readonly="1" name="mto_oper_gravadas" class="form-control form-control-sm text-right op_gravadas" />
                </div>
                <div class="input-group my-2" id="div_op_exoneradas" hidden>
                    <div class="input-group-prepend">
                        <span style="width: 150px;height: 83%;" class="input-group-text">Op. Exoneradas</span>
                    </div>
                    <input type="text" readonly="1" name="mto_oper_exoneradas" class="form-control form-control-sm text-right op_exoneradas" />
                </div>
                <div class="input-group my-2" id="div_op_inafectas" hidden>
                    <div class="input-group-prepend">
                        <span style="width: 150px;height: 83%;" class="input-group-text">Op. Inafectas</span>
                    </div>
                    <input type="text" readonly="1" name="mto_oper_inafectas" class="form-control form-control-sm text-right op_inafectas" />
                </div>
                <div class="input-group my-2">
                    <div class="input-group-prepend">
                        <span style="width: 120px;height: 83%;" class="input-group-text">IGV</span>
                    </div>
                    <div class="input-group-prepend">
                        <span style="height: 83%;" class="input-group-text">
                            <input type="checkbox"  id="check_igv" onchange="nuevaCotizacion.cambiarEstadoIGV()" checked> &nbsp;
                        </span>
                    </div>
                    <input type="text" readonly="1" name="mto_coti_igv" class="form-control form-control-sm text-right mto_igv" />
                </div>
                <div class="input-group my-2">
                    <div class="input-group-prepend">
                        <span style="width: 120px;;height: 83%;" class="input-group-text">TOTAL</span>
                    </div>
                    <input type="text" readonly="1" placeholder="Total" name="total" class="form-control form-control-sm text-right precio_total" autocomplete="off" />
                </div>
            </div>


            <div class="form-group">
                <div class="row" style="display: none">
                    <div class="col">
                        <label class="control-label">Porcent. Descuento</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-tags fa-fw "></i>
                                </span>
                            </div>
                            <input type="number" step="any" min="0" placeholder="Porcent. Descuento" name="dcto_percent" value="0" class="form-control form-control-sm text-right" autocomplete="off" />
                        </div>
                    </div>
                    <div class="col">
                        <label class="control-label">Monto Descuento</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-tags fa-fw "></i>
                                </span>
                            </div>
                            <input type="number" step="any" min="0" placeholder="Monto Descuento" name="dcto_monto" value="0" class="form-control form-control-sm text-right" autocomplete="off" />
                        </div>
                    </div>
                </div>
                <div class="row" style="display: none">
                    <div class="col">

                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-money-check-alt fa-fw "></i>
                                </span>
                            </div>
                            <input type="text" readonly name="nuevo_total" placeholder="Nuevo Total" class="form-control form-control-sm text-right" autocomplete="off" />
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label class="control-label">Nombre Vendedor</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user-check fa-fw "></i>
                                </span>
                            </div>
                            <?php
                            if ($usuario_sesion['rol'] == 'ADM' || $usuario_sesion['rol'] == 'SUPERADMIN') {
                                echo $this->Form->control("usuario_id", ['label' => false, 'value' => $usuario_sesion['id'],  'options' => $usuarios, 'class' => 'form-control form-control-sm']);
                            } else {
                                echo $this->Form->control("usuario_id", ['type' => 'hidden', 'value' => $usuario_sesion['id']]);
                                echo $this->Form->control("usuario_nombre", ['label' => false, 'value' => $usuario_sesion['nombre'], 'class' => 'form-control form-control-sm', 'readonly' => true]);
                            }

                            ?>
                        </div>



                    </div>
                </div>

            </div>
            <div class="form-group">
                <input type="hidden" name="documento_numero" class="form-control" readonly="" />


                <input type="hidden" name="cuenta_id" value="DEFAULT" />
                <input type="hidden" name="persona_id" value="DEFAULT" />

                <input type="hidden" class="detalle_items" name="registros" value="" />
                <br />
                <button type="submit" name="accion" value="venta" id="btnGuardarCoti" class="btn btn-sm btn-primary">
                    <i class="fa fa-save fa-fw"></i> Guardar Proforma
                </button>
            </div>
        </fieldset>
    </div>
</div>
<input type="hidden" name="cotizacion_id" id="cotizacion_id" value="0">
<input type="text" hidden name="reg_items_originales" id="reg_items_originales">

<?= $this->Form->end(); ?>

<div class="modal fade" id="modalBuscadorProductos" tabindex="-1" role="dialog" aria-labelledby="modalBuscadorProductosLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscadorProductosLabel">Productos encontrados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-keyboard fa-fw"></i>
                                </span>
                            </div>
                            <input id="campoBusqueda2" type="text" name="nombre_producto" class="form-control ingresoproducto " placeholder="Busque por Marca / Categoría / Nombre" autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-primary" id="basic-addon2" onclick="nuevaCotizacion.abrirBuscadorProductos()">
                                    <i class="fas fa-search fa-fw"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <table id="tablafiltro" class="" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Marca</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Stock Local</th>
                                    <th>Stock Gral.</th>
                                    <th>Precio Venta</th>
                                    <th>Moneda</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="selCoti" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Cargar Proforma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" name="numero_cotizacion" autocomplete="off" placeholder="# Proforma">
                            <div class="input-group-append">
                                <a class="btn btn-sm btn-primary" href="javascript:nuevaCotizacion.cargarCotizacion()"><i class="fa fa-fw fa-search"></i> Cargar</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-sm btn-primary" href="javascript:nuevaCotizacion.cargarCotizacion('0')"><i class="fa fa-fw fa-search"></i> Aumentar</a>
                    </div>
                    <div class="col-4">
                        <button type="button" class="w-100 btn btn-sm btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->ScriptBlock("var _doctipo_default = '{$tipodoc_defecto}';");
echo $this->Html->ScriptBlock("var servicios = " . json_encode($servicios) . ";");
echo $this->Html->ScriptBlock("var _coti_id = '" . $coti_id . "';");
echo $this->Element('form_items');
echo $this->Element('form_venta_desde_excel');
echo $this->Element('global_editar_item_detalle');
echo $this->Element('global_servicios');
echo $this->Element('global_tipo_cambio');
echo $this->Element('global_listado_productos_vue');
echo $this->Element("global_modal_item_precios");
