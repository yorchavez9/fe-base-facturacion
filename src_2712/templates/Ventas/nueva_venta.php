<?php
echo $this->Form->create(null, ['id' => 'formNuevaVenta', 'onsubmit'   =>  'nuevaVenta.guardar(event)']);
$this->Form->setTemplates(['inputContainer' => '{{content}}'])
?>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-row">
            <div class="col-12 col-sm-12">
                <div class="input-group mt-2">
                    <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-user fa-fw"></i>
                    </span>
                    </div>
                    <input type="text" name="cliente_ruc" autocomplete="off" maxlength="11" class="form-control form-control-sm" placeholder="RUC o DNI" aria-describedby="button-addon2" id="clienteTipoDocInput" >
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-outline-primary estado_busqueda" type="button" id="clienteEstadoBusqueda1" onclick="nuevaVenta.consultaDniRucAjax()">
                            <i class="fas fa-exchange-alt fa-fw"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary estado_busqueda2 "
                                title="Guardar"
                                style="display: none;" type="button" id="clienteEstadoBusqueda2" onclick="nuevaVenta.guardarCliente()">
                            <i class="fas fa-save fa-fw"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary estado_busqueda2 "
                                title="Guardar"
                                style="display: none;" type="button" id="clienteEstadoBusqueda3">
                            <i class="fas fa-check fa-fw"></i>
                        </button>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-outline-primary" type="button" id="button-addon3" onclick="nuevaVenta.clienteLimpiarDatos()">
                            <i class="fas fa-eraser fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="input-group my-2">
            <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fas fa-user fa-fw"></i>
                            </span>
            </div>
            <input type="text" name="cliente_razon_social" autocomplete="off" placeholder="Razón Social" class="form-control form-control-sm" value=""/>
        </div>

        <div class="input-group my-2">
            <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fas fa-map-marker fa-fw"></i>
                            </span>
            </div>
            <input type="text" name="cliente_domicilio_fiscal" autocomplete="off" placeholder="Dirección" class="form-control form-control-sm" value=""/>

            <input type="hidden" name="cliente_id" value=""/>
            <input type="hidden" name="cliente_doc_tipo" value=""/>
            <input type="hidden" name="cliente_doc_numero" value=""/>
            <input type="hidden" name="cliente_persona_tipo" value=""/>
        </div>
    </div>

    <div class="col-12 col-sm-6">
        <div class="form-row my-2">
            <div class="col-6">
                <div class="input-group input-group-sm">
                    <input type="text" name="fecha_venta" class="form-control form-control-sm text-center" value="<?= date("Y-m-d") ?>" readonly />
                    <input type="text" name="hora_venta" class="form-control form-control-sm text-center hora_venta" readonly="true" />
                </div>
            </div>
            <div class="col-6">
                <input type="text" placeholder="Fec. Vencimiento" name="fecha_vencimiento" class="form-control form-control-sm text-center" value="<?=date("Y-m-d")?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="col-sm-12 col-md-6 mb-2" id="formNuevaVentaDocumentoTipo">
                <select name="documento_tipo" class="form-control form-control-sm" onchange="nuevaVenta.setSeries()">
                    <option value="FACTURA">FACTURA</option>
                    <option value="BOLETA">BOLETA</option>
                    <option value="NOTAVENTA">NOTA VENTA</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-6 mb-2" id="formNuevaVentaTipoCambio" style="display: none;" >
                <div class="input-group input-group-sm">
                    <select name="forma_pago" class="form-control form-control-sm"  >
                        <option value="CONTADO">CONTADO</option>
                        <option value="CREDITO">CREDITO</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-row">
                    <div class="d-none d-sm-block col-12 mb-2">
                        <select name="tipo_moneda" class="form-control form-control-sm">
                            <option value="PEN">Soles</option>
                            <option value="USD">Dólares</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="form-row">
                    <div class="col-12  mb-2">
                        <div class="row">
                            <div class="d-none d-sm-block col-12 col-lg-12">
                                <a href="javascript:nuevaVenta.showModalCoti()" class="btn btn-sm btn-primary w-100"><i class="fa fa-fw fa-search"></i> Proforma</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-row" id="documento_serie" >
                    <div class="d-sm-block col-12  mb-2">
                        <select name="documento_serie" class="form-control form-control-sm" required>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-row">
                    <div class="d-sm-block col-12  mb-2">
                        <select name="establecimiento_id" class="form-control form-control-sm" onchange="nuevaVenta.setSeries()">
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="d-none d-sm-block col-sm-3">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-barcode fa-fw"></i>
                    </span>
            </div>
            <input class="form-control form-control-sm ingresocodigo" name="codigo" autocomplete="off" placeholder="Código + Tecla Enter" />
        </div>
    </div>
    <div class="col-12 col-sm-9">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-keyboard fa-fw"></i>
                    </span>
            </div>
            <input id="campoBusqueda1" type="text" name="nombre_producto" class="form-control form-control-sm ingresoproducto " placeholder="Busque por Marca / Categoría / Nombre" autocomplete="off" >
            <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-primary" id="basic-addon2" onclick="nuevaVenta.abrirBuscadorProductos()">
                    <i class="fas fa-search fa-fw d-block d-sm-none"></i> <span class="d-none d-sm-block"><i class="fas fa-search fa-fw"></i> Buscar</span>
                </button>
            </div>
            <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-success" onclick="FormItems.Nuevo()">
                    <i class="fas fa-plus fa-fw fa-fw d-block d-sm-none"></i> <span class="d-none d-sm-block"><i class="fas fa-plus fa-fw fa-fw"></i> Producto</span>
                </button>
            </div>
        </div>
    </div>
</div>
<hr/>

<div class="row">
    <div class="col-12">
        <fieldset >
            <div class="row justify-content-between">
                <div class="col-md-2 align-middle">
                    <h6>Detalle la Venta</h6>
                </div>
                <div class="col-md-10">
                    <div class="text-right">
                        <div class="row mb-2">
                            <div class="col-md-9 d-flex text-right">
                                <a href="javascript:nuevaVenta.abrirModalServicio();" class="btn btn-primary btn-sm  mb-auto mx-auto">Venta de Servicio</a>
                            </div>
                            <div class="col-md-3 text-right" id="div_tipo_cambio" >
                                <a href="javascript:void(0)" id="linkTipoCambio" onclick="GlobalFormTipoCambio.abrir()" ></a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 25px;">#</th>
                        <th style="width: 150px;">Código</th>
                        <th style="width: 100px">Foto</th>
                        <th>Producto</th>
                        <th style="width: 100px;">Unidad</th>
                        <th style="width: 80px;">Cantidad</th>
                        <th style="width: 100px;">P. Unit</th>
                        <th style="width: 120px;">Totales</th>
                        <th style="width: 80px;"></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="detalle">

                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <fieldset>
            <h6>Comentarios Adicionales</h6>
            <textarea class="form-control form-control-sm" name="comentarios" placeholder="Comentarios Adicionales" ></textarea>
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 offset-0 offset-md-6">
        <h6 class="mt-3">Resumen</h6>
        <span class="nv-caption">
            <small>
                NOTA : Las notas de venta no tienen válidez fiscal
            </small>
        </span>
        <div class="form-group">
            <div class="input-group my-2" id="div_op_gravadas">
                <div class="input-group-prepend">
                    <span style="width: 150px;height: 83%;" class="input-group-text">Op. Gravadas</span>
                </div>
                <input type="text" readonly="1" name="op_gravadas" class="form-control form-control-sm text-right op_gravadas"/>
            </div>
            <div class="input-group my-2" id="div_op_exoneradas" hidden>
                <div class="input-group-prepend">
                    <span style="width: 150px;height: 83%;" class="input-group-text">Op. Exoneradas</span>
                </div>
                <input type="text" readonly="1" name="op_exoneradas" class="form-control form-control-sm text-right op_exoneradas"/>
            </div>
            <div class="input-group my-2" id="div_op_inafectas" hidden>
                <div class="input-group-prepend">
                    <span style="width: 150px;height: 83%;" class="input-group-text">Op. Inafectas</span>
                </div>
                <input type="text" readonly="1" name="op_inafectas" class="form-control form-control-sm text-right op_inafectas"/>
            </div>
            <div class="input-group my-2" hidden >
                <div class="input-group-prepend">
                    <span style="width: 120px;height: 83%;" class="input-group-text">SUB TOTAL</span>
                </div>
                <input type="text" readonly="1" name="subtotal" class="form-control form-control-sm text-right mto_subtotal"/>
            </div>
            <div class="input-group my-2">
                <div class="input-group-prepend">
                    <span style="width: 120px;height: 83%;" class="input-group-text">IGV</span>
                </div>
                <div class="input-group-prepend" id="check_igv">
                    <span style="height: 83%;" class="input-group-text">
                        <input type="checkbox" name="igv_input" onchange="nuevaVenta.changeCheckIGV()" checked> &nbsp;
                    </span>
                </div>
                <input type="text" readonly="1" name="igv" class="form-control form-control-sm text-right mto_igv"/>
            </div>
            <div class="input-group my-2">
                <div class="input-group-prepend">
                    <span style="width: 120px;;height: 83%;" class="input-group-text">TOTAL</span>
                </div>
                <input type="text" readonly="1" name="total" class="form-control form-control-sm text-right precio_total"/>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <div class="form-group">
                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                        </div>
                        <?php
                        if ($usuario_sesion['rol'] == 'ADM'){
                            echo $this->Form->control("usuario_id", ['label' => false, 'value'=> $usuario_sesion['id'],  'options' => $usuarios, 'class' => 'form-control form-control-sm']);
                        }else{
                            echo $this->Form->control("usuario_id", ['type'=> 'hidden', 'value' => $usuario_sesion['id']]);
                            echo $this->Form->control("usuario_nombre", ['label' => false, 'value' => $usuario_sesion['nombre'], 'class' => 'form-control form-control-sm','readonly' => true]);
                        }

                        ?>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="form-group">
                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-money-bill fa-fw"></i></span>
                        </div>
                        <input type="text" placeholder="Pago Inicial" name="pago_inicial" class="form-control form-control-sm text-right" required="true" autocomplete="off" />
                        <div class="input-group-append">
                            <?=
                                $this->Form->control("medio_pago_venta",
                                [
                                    'placeholder'   =>  'Seleccione método de pago',
                                    'label'         =>  false,
                                    'style'         =>  'max-width:130px',
                                    'class'         =>  'form-control form-control-sm',
                                    'options'       =>  $metodos_pago
                                ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7 offset-5" id="div_otro_pago" style="display: none;">
                <div class="form-group">
                    <div class="input-group my-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-cash-register fa-fw"></i></span>
                        </div>
                        <input type="text" placeholder="Otro metodo de pago" name="medio_pago_otro" class="form-control form-control-sm text-right" autocomplete="off" />
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group text-right">

            <input type="hidden" name="documento_numero" class="form-control"  readonly="" />
            <input type="hidden" name="cuenta_id" value="DEFAULT"/>
            <input type="hidden" name="persona_id" value="DEFAULT"/>
            <input type="hidden" class="detalle_items" name="registros" value="" id="registrosAll"/>
            <br/>
            <div id="botones_venta">
                <button type="submit" name="accion" value="venta" class="btn btn-sm btn-primary" id="btn_guardar_venta" >
                  <i class="fa fa-save fa-fw"></i>   Guardar Venta
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selCoti" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Ingrese número de proforma</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control form-control-sm" name="numero_cotizacion" autocomplete="off" placeholder="# Proforma" >
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <a class="btn btn-sm btn-primary" href="javascript:nuevaVenta.cargarCotizacion()"><i class="fa fa-fw fa-search"></i> Cargar Proforma</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="0" name="monto_credito_total">
<input type="hidden" name="forma_pago_cuotas">
<input type="text" hidden name="tipo_detraccion_codigo" id="tipo_detraccion_codigo" value="0" >

<?= $this->Form->end();?>

<div class="modal fade" id="modalBuscadorProductos" tabindex="-1" role="dialog" aria-labelledby="modalBuscadorProductosLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalBuscadorProductosLabel">Buscador de Productos</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-3">


                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-keyboard fa-fw"></i>
                                </span>
                            </div>
                            <input id="campoBusqueda2" type="text" name="nombre_producto" class="form-control ingresoproducto "
                                   placeholder="Busque por Marca / Categoría / Nombre" autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-primary" id="basic-addon2" onclick="nuevaVenta.abrirBuscadorProductos()">
                                    <i class="fas fa-search fa-fw"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tablafiltro" class="" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Categoría</th>
                                    <th>Marca</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Ubicación</th>
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
</div>
<div class="modal fade" id="modalCuotas" tabindex="-1" role="dialog" aria-labelledby="modalCuotasLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCuotasLabel">Seleccionar Cuotas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-fw fa-money-bill"></i> &nbsp; Total
                                </span>
                            </div>
                            <input type="number" step="any" min="0" name="monto_total" class="form-control form-control-sm" placeholder="Monto Total"  >
                        </div>
                    </div>

                    <div class="col">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    # Nro Cuotas
                                </span>
                            </div>

                            <input type="number" step="any" min="0" name="nro_cuotas" class="form-control form-control-sm" placeholder="Nro de Cuotas" value="2" >
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                            </tr>
                            </thead>
                            <tbody id="tablaCuotas">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="nuevaVenta.guardarCuotas()" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<?php
$allowed_factura = '0';
$allowed_boleta = '0';
echo $this->Html->ScriptBlock("var _doctipo_default = '{$tipodoc_defecto}';");
echo $this->Html->ScriptBlock("var _editar_fecha_emision = '{$editar_fecha_emision}';");
echo $this->Html->ScriptBlock("var _autosavecliente = '{$ven_autosavecliente}';");
echo $this->Html->ScriptBlock("var servicios = " . json_encode($servicios) . ";");
echo $this->Html->ScriptBlock("var _coti_id = '" . $coti_id . "';");
echo $this->Html->ScriptBlock("var _ntventa_id = '" . $ntventa_id . "';");
echo $this->Html->ScriptBlock("var _series = [];");
echo $this->Html->ScriptBlock("var _is_allowed_factura = {$allowed_factura};" );
echo $this->Html->ScriptBlock("var _is_allowed_boleta = false ;" );
echo $this->Html->ScriptBlock("var establecimiento_default = '{$establecimiento_default}' ;" );
echo $this->Element('form_items');
echo $this->Element('form_venta_desde_excel');
echo $this->Element('global_editar_item_detalle');
echo $this->Element('global_tipo_cambio');
echo $this->Element('modal_imprimir_venta');
echo $this->Element('global_servicios');
echo $this->Element('modal_detracciones',[ 'codigo_detracciones' =>  $codigo_detracciones]);
echo $this->Element("global_listado_productos_vue");
echo $this->Element("global_modal_item_precios");
