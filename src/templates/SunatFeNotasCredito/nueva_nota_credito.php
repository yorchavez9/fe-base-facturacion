<style>
    .nc_formatototales_input{
        border: none;
        border-bottom: 1px gray solid;
        text-align: right;
    }
    .nc_formatototales_input:read-only{
        color: gray;
        background-color: #DEDEDE;
    }
</style>
<div class="alert alert-warning">
    <strong>Importante: </strong> La emisión de Notas de Crédito suele presentar errores, para anular boletas o facturas recomendamos usar el <strong>Comunicado de Baja</strong>, en caso de requerir
    una Nota de Crédito, por favor notificar a soporte para hacer el debido seguimiento.
</div>
<form id="notaCreditoModificacionForm" method="post" onsubmit="NotaCreditoModificacion.submit(event)">
    <div class="row">
        <div class="col-3">
            <label>Tipo Comprobante:</label>

            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <label class="input-group-text" >
                        <i class="fas fa-file-invoice fa-fw" ></i>
                    </label>
                </div>
                <input type="hidden" name="tipo_doc" value="07"/>
                <input type="text" value="Nota de Crédito" class="form-control" readonly/>

            </div>

        </div>

        <div class="col-3">
            <label>Serie:</label>
            <div class="input-group input-group-sm ">
                <div class="input-group-prepend">
                    <label class="input-group-text">
                        <i class="fa fa-qrcode fa-fw"></i>
                    </label>
                </div>

                <select name="serie" id="serie"  class="form-control form-control-sm">

                </select>
            </div>
        </div>

        <div class="col-6">
            <label id="lbTipoNota">Tipo Nota de Credito:</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <label class="input-group-text">
                        <i class="fa fa-paste fa-fw" ></i>
                    </label>
                </div>
                <select name="codigo_motivo_credito" class="form-control form-control-sm" onchange="actualizarBloqueosCampos()" >
                    <option value="01">01 - Anulación de la operación</option>
                    <option value="02">02 - Anulación por error en el RUC</option>
                    <option value="03">03 - Corrección por error en la descripción</option>
                    <!--option value="04">Descuento global</option -->
                    <!--option value="05">Descuento por ítem</option -->
                    <option value="06">06 - Devolución total</option>
                    <option value="07">07 - Devolución parcial</option>
<!--                    <option value="08">Bonificación</option>-->
                    <option value="09">09 - Disminución en el valor</option>
<!--                    <option value="10">Otros conceptos</option>-->
<!--                    <option value="11">Ajustes de operaciones de exportación</option>-->
<!--                    <option value="12">Ajustes afectos al IVAP</option>-->
                    <option value="13">13 - Ajustes de Monto y/o Fecha de Pago</option>
                </select>
            </div>
        </div>

    </div>
    <br>



    <div class="row">
        <div class="col-3">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text">
                            <i class="fa fa-id-card fa-fw" ></i>
                        </label>
                    </div>
                    <input name="cliente_doc_numero" placeholder="Documento del Cliente" class="form-control" value="<?php // $venta->cliente_doc_numero?>" readonly />
                    <input name="cliente_doc_tipo" class="form-control" value="<?php // $venta->cliente_doc_tipo?>" type="hidden" />
                </div>
        </div>
        <div class="col-3">

                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text">
                            <i class="fa fa-signature fa-fw"></i>
                        </label>
                    </div>
                    <input name="cliente_razon_social" placeholder="Cliente Razon Social" class="form-control" value="<?php // $venta->cliente_razon_social?>" readonly />
                </div>

        </div>

        <div class="col-6">


                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text">
                            <i class="fa fa-file-alt fa-fw"></i>
                        </label>
                    </div>
                    <input type="text" placeholder="Descripcion" class="form-control" name="descripcion" value="" autocomplete="off" required>
                </div>

        </div>

    </div>
    <br>
    <div class="row">
        <div class="col-6">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <label class="input-group-text">
                        <i class="fa fa-info fa-fw"></i>
                    </label>
                </div>
                <input type="text" class="form-control" placeholder="Serie-Correlativo" name="num_doc_afectado" value="<?php //$venta->documento_serie?>-<?php //$venta->documento_correlativo?>" readonly autocomplete="off">
            </div>
        </div>
        <div class="col-2">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend fecha_emision_hidden" >
                    <label class="input-group-text">
                        <i class="fa fa-calendar-alt fa-fw"></i>
                    </label>
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="Fec. Emision" name="fecha_emision" value="<?= date("Y-m-d") ?>" readonly autocomplete="off">
            </div>
        </div>
        <div class="col-2">

            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <label class="input-group-text">
                        Moneda
                    </label>
                </div>
                <input type="text" placeholder="Moneda" name="tipo_moneda" class="form-control" value="<?php // $venta->tipo_moneda?>" readonly autocomplete="off">
            </div>

        </div>
        <div class="col-2">

            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <label class="input-group-text">
                        Monto
                    </label>
                </div>
                <input type="text" placeholder="Moneda" name="" id="factura_total" class="form-control" value="<?php // $venta->total?>" readonly autocomplete="off">
            </div>

        </div>
        <div class="col-6 pt-3">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <label class="input-group-text">
                        F. Emision del Documento afectado
                    </label>
                </div>
                <input type="text" class="form-control" placeholder="" name="" id="factura_f_venta" value="<?php // $venta->fecha_venta->format("d-m-Y H:i") ?>" readonly autocomplete="off">
            </div>
        </div>
    </div>

    <br>
    <div id="nc_items_container">
    <div class="row">
        <div class="col-12">
            <table class="table table-responsive-xl">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>
                        Sub Total<br/>
                        IGV<br/>
                        Total
                    </th>
                </tr>
                </thead>
                <tbody id="detalle_items">
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div id="nc_fechamontopago_container" style="display: none">
                <h3>Fechas y Monto de Pago</h3>
                <p>
                    <b>Importante</b>:<br/>
                    La suma de los montos debe ser el nuevo total de la factura a modificar, el cálculo de la sumatoria es manual.<br/>
                    <br/>
                    <b>Monto Original:</b> <span class="tipo_mon_factura"> </span> <?php // $venta->tipo_moneda?> <span id="nc_monto_original"><?php // $venta->total?></span><br/>
                    <b>Nuevo Monto:</b> <span class="tipo_mon_factura"> </span>   <span id="nc_suma_cuotas"></span><br/>
                </p>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input id="formapago_fecha1" type="text" name="formapago_fecha[1]" class="form-control form-control-sm"></td>
                            <td><input type="text" name="formapago_monto[1]" class="nueva_cuota form-control form-control-sm" onchange="sumarCuotas()"></td>
                        </tr>
                        <tr>
                            <td><input id="formapago_fecha2" type="text" name="formapago_fecha[2]" class="form-control form-control-sm"></td>
                            <td><input type="text" name="formapago_monto[2]" class="nueva_cuota form-control form-control-sm" onchange="sumarCuotas()"></td>
                        </tr>
                        <tr>
                            <td><input id="formapago_fecha3" type="text" name="formapago_fecha[3]" class="form-control form-control-sm"></td>
                            <td><input type="text" name="formapago_monto[3]" class="nueva_cuota form-control form-control-sm" onchange="sumarCuotas()"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group my-2">
                <label>Subtotal</label>
                <input type="text" name="mto_oper_gravadas" class="form-control"  value="<?php //$venta->op_gravadas?>" id="sum_subtotal" style="text-align: right;" readonly/>
            </div>
            <div class="form-group my-2">
                <label>IGV</label>
                <input type="text" name="mto_igv" class="form-control" value="<?php //$venta->igv_monto?>" id="sum_igv"  style="text-align: right;"  readonly/>
            </div>
            <div class="form-group my-2">
                <label>Total </label>
                <input type="text" name="mto_imp_venta" class="form-control" value="<?php //$venta->total?>" id="sum_total"  style="text-align: right;"  readonly/>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <input type="hidden" name="factura_id" value="0">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>

</form>

<div id="modalEnviandoNotaCredito" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Espere por favor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <i class="fas fa-spin fa-fw fa-spinner"></i>
                Espere mientras enviamos el documento a Sunat
            </div>
        </div>
    </div>
</div>

<?php

echo $this->Html->ScriptBlock("var factura_id = '{$factura_id}';");