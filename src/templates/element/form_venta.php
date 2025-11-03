<div class="modal fade"
     id="formVentaModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formVentaLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formVentaLabel">Registro de Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formVenta" onsubmit="FormVenta.Guardar(event)">
                    <div class="row align-items-end">
                        <div class="col-6 col-md-3 mb-2">
                            <label>ID: </label>
                            <input type="text" name="id" class="form-control" readonly>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <label>Usuario ID: </label>
                            <input type="text" name="usuario_id" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <label>Factura ID: </label>
                            <input type="text" name="factura_id" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <label>Almacen ID: </label>
                            <input type="text" name="almacen_id" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label>Emisor RUC: </label>
                            <input type="text" name="emisor_ruc" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <label>Emisor Razón Social: </label>
                            <input type="text" name="emisor_razon_social" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="row align-items-end">
                        <div class="col-6 col-lg-4 mb-2">
                            <label>Cliente ID: </label>
                            <input type="text" name="cliente_id" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-6 col-lg-4 mb-2">
                            <label>Cliente Doc Tipo: </label>
                            <select name="cliente_doc_tipo" class="form-control">
                                <option value="">- No Seleccionado -</option>
                                <option value="1">DNI - Libreta Electoral</option>
                                <option value="4">Carnet de Extranjeria</option>
                                <option value="6">RUC</option>
                                <option value="7">Pasaporte</option>
                                <option value="11">Part. de Nacimiento-Identidad</option>
                                <option value="0">Otros</option>
                            </select>
                        </div>

                        <div class="col-6 col-lg-4 mb-2">
                            <label>Cliente Doc Número: </label>
                            <input type="text" name="cliente_doc_numero" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label>Cliente Razón Social: </label>
                            <input type="text" name="cliente_razon_social" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <label>Cliente Domicilio Fiscal: </label>
                            <input type="text" name="cliente_domicilio_fiscal" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Documento Tipo: </label>
                            <select name="documento_tipo" class="form-control">
                                <option value="">- No Seleccionado -</option>
                                <option value="BOLETA">BOLETA</option>
                                <option value="FACTURA">FACTURA</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Documento Serie: </label>
                            <input type="text" name="documento_serie" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-lg-4 mb-2">
                            <label>Documento Correlativo: </label>
                            <input type="text" name="documento_correlativo" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 col-lg-9 mb-2">
                            <label>Nombre Único: </label>
                            <input type="text" name="nombre_unico" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg mb-2">
                            <label>Estado: </label>
                            <input type="text" name="estado" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 col-lg mb-2">
                            <label>Sunat CDR-RC: </label>
                            <input type="text" name="sunat_cdr_rc" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg mb-2">
                            <label>Sunat CDR-MSG: </label>
                            <input type="text" name="sunat_cdr_msg" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg mb-2">
                            <label>Estado Sunat: </label>
                            <input type="text" name="estado_sunat" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Subtotal: </label>
                            <input type="text" name="subtotal" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>IGV Percent: </label>
                            <input type="text" name="igv_percent" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>IGV Monto: </label>
                            <input type="text" name="igv_monto" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Op Gravadas</label>
                            <input type="text" name="op_gravadas" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Op Gratuitas: </label>
                            <input type="text" name="op_gratuitas" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Op Exoneradas: </label>
                            <input type="text" name="op_exoneradas" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Op Inafectas: </label>
                            <input type="text" name="op_inafectas" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Total: </label>
                            <input type="text" name="total" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <br>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-lg-3 mb-2">
                            <label>Total en Letras: </label>
                            <input type="text" name="total_en_letras" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Total Pagos: </label>
                            <input type="text" name="total_pagos" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Total Deuda: </label>
                            <input type="text" name="total_deuda" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                            <label>Total Items: </label>
                            <input type="text" name="total_items" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Fecha Venta: </label>
                            <input type="text" name="fecha_venta" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Fecha Por Anular: </label>
                            <input type="text" name="fecha_poranular" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Tipo Moneda: </label>
                            <input type="text" name="tipo_moneda" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-12">
                            <label>Comentarios: </label>
                            <input type="text" name="comentarios" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <br>

                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Guía Remisión: </label>
                            <input type="text" name="guia_remision" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Cod Vendedor: </label>
                            <input type="text" name="codvendedor" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Nro Ref: </label>
                            <input type="text" name="nro_ref" class="form-control" autocomplete="off">
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <label>Forma Pago: </label>
                            <input type="text" name="forma_pago" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Guardar</button>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>
</div>

<script>
    var FormVenta = {

        IdFormulario: 'formVenta',

        IdModal: 'formVentaModal',

        Limpiar: function () {
            this.setCampo('id', '');
            this.setCampo('usuario_id', '');
            this.setCampo('factura_id', '');
            this.setCampo('almacen_id', '');
            this.setCampo('emisor_ruc', '');
            this.setCampo('emisor_razon_social', '');
            this.setCampo('cliente_id', '');
            this.setCampo('cliente_doc_tipo', '');
            this.setCampo('cliente_doc_numero', '');
            this.setCampo('cliente_razon_social', '');
            this.setCampo('cliente_domicilio_fiscal', '');
            this.setCampo('documento_tipo', '');
            this.setCampo('documento_serie', '');
            this.setCampo('documento_correlativo', '');
            this.setCampo('nombre_unico', '');
            this.setCampo('estado', '');
            this.setCampo('sunat_cdr_rc', '');
            this.setCampo('sunat_cdr_msg', '');
            this.setCampo('estado_sunat', '');
            this.setCampo('subtotal', '');
            this.setCampo('igv_percent', '');
            this.setCampo('igv_monto', '');
            this.setCampo('op_gravadas', '');
            this.setCampo('op_gratuitas', '');
            this.setCampo('op_exoneradas', '');
            this.setCampo('op_inafectas', '');
            this.setCampo('total', '');
            this.setCampo('total_en_letras', '');
            this.setCampo('total_pagos', '');
            this.setCampo('total_deuda', '');
            this.setCampo('total_items', '');
            this.setCampo('fecha_venta', '');
            this.setCampo('fecha_poranular', '');
            this.setCampo('tipo_moneda', '');
            this.setCampo('comentarios', '');
            this.setCampo('guia_remision', '');
            this.setCampo('codvendedor', '');
            this.setCampo('nro_ref', '');
            this.setCampo('forma_pago', '');
        },

        setCampo  : function(field, value){
            $(`#${this.IdFormulario} [name=${field}]`).val(value);
        },

        getCampo  : function(field){
            return $(`#${this.IdFormulario} [name=${field}]`).val();
        },

        getDatos: function () {
            var formElement = document.getElementById(this.IdFormulario);
            var formData = new FormData(formElement);

            return formData;
        },

        setDatos: function (d) {
            this.setCampo('id', d.id);
            this.setCampo('usuario_id', d.usuario_id);
            this.setCampo('factura_id', d.factura_id);
            this.setCampo('almacen_id', d.almacen_id);
            this.setCampo('emisor_ruc', d.emisor_ruc);
            this.setCampo('emisor_razon_social', d.emisor_razon_social);
            this.setCampo('cliente_id', d.cliente_id);
            this.setCampo('cliente_doc_tipo', d.cliente_doc_tipo);
            this.setCampo('cliente_doc_numero', d.cliente_doc_numero);
            this.setCampo('cliente_razon_social', d.cliente_razon_social);
            this.setCampo('cliente_domicilio_fiscal', d.cliente_domicilio_fiscal);
            this.setCampo('documento_tipo', d.documento_tipo);
            this.setCampo('documento_serie', d.documento_serie);
            this.setCampo('documento_correlativo', d.documento_correlativo);
            this.setCampo('nombre_unico', d.nombre_unico);
            this.setCampo('estado', d.estado);
            this.setCampo('sunat_cdr_rc', d.sunat_cdr_rc);
            this.setCampo('sunat_cdr_msg', d.sunat_cdr_msg);
            this.setCampo('estado_sunat', d.estado_sunat);
            this.setCampo('subtotal', d.subtotal);
            this.setCampo('igv_percent', d.igv_percent);
            this.setCampo('igv_monto', d.igv_monto);
            this.setCampo('op_gravadas', d.op_gravadas);
            this.setCampo('op_gratuitas', d.op_gratuitas);
            this.setCampo('op_exoneradas', d.op_exoneradas);
            this.setCampo('op_inafectas', d.op_inafectas);
            this.setCampo('total', d.total);
            this.setCampo('total_en_letras', d.total_en_letras);
            this.setCampo('total_pagos', d.total_pagos);
            this.setCampo('total_deuda', d.total_deuda);
            this.setCampo('total_items', d.total_items);
            this.setCampo('fecha_venta', d.fecha_venta);
            this.setCampo('fecha_poranular', d.fecha_poranular);
            this.setCampo('tipo_moneda', d.tipo_moneda);
            this.setCampo('comentarios', d.comentarios);
            this.setCampo('guia_remision', d.guia_remision);
            this.setCampo('codvendedor', d.codvendedor);
            this.setCampo('nro_ref', d.nro_ref);
            this.setCampo('forma_pago', d.forma_pago);
        },

        Cerrar : function () {
            $(`#${this.IdModal}`).modal('hide');
            this.Limpiar();
        },

        Guardar: function (e) {
            e.preventDefault();
            var endpoint = `${base}ventas/save`;
            var datos = this.getDatos();
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                success: function (r) {
                    if (!r.success) {
                        alert(r.message);
                    } else {
                        FormVenta.Cerrar();
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },

        Editar: function (id) {
            $(`#${this.IdModal}`).modal('show');
            this.fetchDatos(id);
        },

        fetchDatos: function(id) {
            var endpoint = `${base}ventas/get-one/${id}`;
            $.ajax({
                url: endpoint,
                data: {},
                type:'GET',
                success : function(r){
                    if(r.success){
                        FormVenta.setDatos(r.data);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });
        }
    };
</script>





