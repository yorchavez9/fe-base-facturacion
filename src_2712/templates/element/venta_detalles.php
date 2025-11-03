<div class="modal fade" id="modalVentaDetalles" tabindex="-1"
     role="dialog" aria-labelledby="modalVentaDetallesLabel" aria-hidden="true"
     data-backdrop="static" data-keyboard="false"
>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="modalVentaDetallesLabel"> <i class="fa fa-mail-bulk fa-fw"></i> Detalles de Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalVentaDetalles.Limpiar()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-3">
<!--                -->


<p>
    Enlaces de Interés:
    <a href="https://www.sunat.gob.pe/ol-ti-itconsvalicpe/ConsValiCpe.htm" target="_blank">Validez CPE</a> |
    <a href="http://www.sunat.gob.pe/ol-ti-itconsverixml/ConsVeriXml.htm" target="_blank">Validez XML</a>
</p>
<!--<p>-->
<!--    Opciones :-->
<!---->
<!--</p>-->
<hr/>
<div class="row">
    <div class="col-md-12">
        <fieldset>
            <h6>Información del Cliente</h6>
            <table class="table table-sm table-bordered">
                <tr>
                    <th>Emisor</th>
                    <td id="emisor"></td>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <td id="cliente">
                    </td>
                </tr>
                <tr>
                    <th>Serie Correlativo</th>
                    <td id="serie_correlativo"></td>
                </tr>
                <tr>
                    <th>Fecha de Venta</th>
                    <td id="fecha_venta"></td>
                </tr>
                <tr>
                    <th>Monto Subtotal</th>
                    <td id="monto_subtotal">
                    </td>
                </tr>
                <tr>
                    <th>Monto IGV</th>
                    <td id="monto_igv">
                    </td>
                </tr>
                <tr>
                    <th>Monto Total</th>
                    <td id="monto_total">
                    </td>
                </tr>

            </table>
        </fieldset>
        <fieldset>
            <h6 id="titulo_items"></h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 150px">Código</th>
                            <th>Descripción</th>
                            <th style="width: 150px">Cantidad</th>
                            <th colspan="2" >P. Unitario</th>
                            <th colspan="2" >P. Total</th>
                            <?php if ($usuario_sesion['rol'] == 'SUPERADMIN') : ?>
<!--                                <th>Acciones</th>-->
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="listado_detalles">

                    </tbody>
                </table>
            </div>
        </fieldset>
        <fieldset>
            <h6>Comentarios Adicionales</h6>
            <p id="comentarios_adicionales"></p>
        </fieldset>
    </div>
</div>

<?php
   // echo $this->Html->ScriptBlock('var all_items = ' . json_encode($all_items) . "; var cpe_id = '{$venta->id}'; var cpe_tipo = '{$venta->documento_tipo}';");
    echo $this->Element('form_venta');
    echo $this->Element('form_factura');
    echo $this->Element('form_venta_registro');
    echo $this->Element('modal_descargar_enviar');
    ?>

<!--                -->
            </div>
        </div>
    </div>
</div>

<script>
    var ModalVentaDetalles = {
        Init: function () {
            $("#btnDescargar").click(function(e){
                e.preventDefault()
                ModalDescargarEnviar.Abrir(cpe_tipo,cpe_id);
            })
        },

        Limpiar: function () {
            $('#listado_detalles').html("");
        },

        Abrir: function (venta_id) {
            ModalVentaDetalles.Limpiar();
            $('#modalVentaDetalles').modal('show');

            $.ajax({
                url: base + "ventas/api-detalles/" + venta_id,
                data: '',
                type: 'POST',
                success: function (r) {
                    // console.log(r)
                    var venta = r.data.venta;
                    var emisor = r.data.emisor;

                    $('#link_generar_enviar').html(
                        `<a href="${base}sunat-fe-facturas/generar-xml/${venta_id}">Generar XML y Enviar</a>`
                    );

                    if (venta.documento_tipo != "NOTAVENTA") {
                        $('#link_generar_pdfs').html(
                            `<a href="${base}sunat-fe-facturas/regenerar-pdfs/${venta_id}">Sólo Generar PDFs</a>`
                        );
                    } else {
                        $('#link_generar_pdfs').html(
                            `<a href="${base}ventas/regenerar-pdfs/${venta_id}">Sólo Generar PDFs</a>`
                        );
                    }

                    $('#links_editar_registro').html(
                        `<a href="javascript:FormVenta.Editar(${venta_id})"><i class="fa fa-fw fa-edit"></i> Venta</a> |
                            <a href="javascript:FormFactura.Editar(${venta_id})"><i class="fa fa-fw fa-edit"></i> Factura</a>`
                    );

                    $('#emisor').html(
                        emisor.ruc + `: ` + emisor.razon_social
                    );

                    $('#cliente').html(
                        venta.cliente_doc_numero + `: ` + venta.cliente_razon_social
                    );

                    $('#serie_correlativo').html(
                        venta.documento_serie + `-` + venta.documento_correlativo
                    );

                    $('#fecha_venta').html(
                        venta.fecha_venta.substr(0, 10)
                    );

                    $('#monto_subtotal').html(
                        venta.tipo_moneda + " " + venta.subtotal
                    );

                    $('#monto_igv').html(
                        venta.tipo_moneda + " " + venta.igv_monto
                    );

                    $('#monto_total').html(
                        venta.tipo_moneda + " " + venta.total
                    );

                    $('#titulo_items').html(
                        "Items de la " + venta.documento_tipo
                    );

                    var strItems = "";
                    $(venta.venta_registros).each(function (i, o) {
                        strItems += `<tr>
                        <td>${o.item_index}</td>
                        <td>${o.item_codigo}</td>
                        <td>${o.item_nombre}</td>
                        <td>${o.cantidad} ${o.item_unidad}</td>
                        <td>${venta.tipo_moneda}</td>
                        <td>${o.precio_uventa}</td>
                        <td>${venta.tipo_moneda}</td>
                        <td>${o.precio_total}</td>
                        </tr>`
                    });

                    $('#listado_detalles').html(strItems);

                    $('#comentarios_adicionales').html(venta.comentarios != "" ? venta.comentarios : "Sin comentarios");
                }
            });



            // $('#link_generar_pdfs_2').html(
            //     `<a href="${base}ventas/regenerar-pdfs/${venta_id}">Sólo Generar PDFs</a>`
            // );
        }
    };
</script>
