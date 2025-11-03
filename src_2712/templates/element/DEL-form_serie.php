<div class="modal fade"
     id="formSeriesModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formSeriesLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formSeriesLabel">Informacion de la serie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formSeries" onsubmit="FormSeries.Guardar(event)">
                    <!--<div class="row">
                        <div class="col">
                            <label>Para el almacen:</label>
                            <select name="almacen_id" class="form-control"></select>
                        </div>
                    </div>-->

                    <br>

                    <div class="row">
                        <div class="col">
                            <label>Emisor: </label>
                            <select name="emisor_id" class="form-control"></select>
                        </div>
                    </div>

                    <br>

                    <div class="row">

                        <div class="col">
                            <label>Tipo de Documento:</label>
                            <select name="tipo" class="form-control">
                                <option value="FACTURA">FACTURA ELECTRÓNICA</option>
                                <option value="BOLETA">BOLETA DE VENTA ELECTRONICA</option>
                                <option value="FACTURA_NC">NOTA DE CRÉDITO - FACTURA</option>
                                <option value="BOLETA_NC">NOTA DE CRÉDITO - BOLETA</option>
                                <option value="FACTURA_ND">NOTA DE DÉBITO - FACTURA</option>
                                <option value="BOLETA_ND">NOTA DE DÉBITO - BOLETA</option>
                                <option value="GUIA_REMITENTE">GUIA DE REMISIÓN REMITENTE</option>
                                <option value="GUIA_TRANSPORTISTA">GUIA DE REMISIÓN TRANSPORTISTA</option>
                            </select>
                        </div>

                    </div>

                    <br>

                    <div class="row align-items-end">
                        <div class="col-3">
                            <label>Serie:</label>
                            <input type="text" name="serie" maxlength="4" class="form-control" autocomplete="off">
                        </div>

                        <div class="col-3">
                            <label>Correlativo:</label>
                            <input type="text" name="correlativo" class="form-control" autocomplete="off">
                        </div>

                        <div class="col">
                            <input type="hidden" name="id">
                            <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>

                    <input type="hidden" name="id">
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    var FormSeries = {
        IdFormulario: 'formSeries',

        IdModal: 'formSeriesModal',

        guardar_callback: null,

        setEventoGuardar: function (fn) {
            this.guardar_callback = fn;
        },

        Init: function () {

        },

        Nuevo: function (emisor_id) {
            $(`#${this.IdModal}`).modal('show');
            this.Limpiar();
            this.poblarEmisores(emisor_id);
        },

        Limpiar: function () {
            this.setCampo('serie', '');
            this.setCampo('id', '');
            this.setCampo('serie', '');
            this.setCampo('correlativo', '');
        },

        setCampo  : function(field, value){
            $(`#${this.IdFormulario} [name=${field}]`).val(value);
        },
        getCampo  : function(field){
            return $(`#${this.IdFormulario} [name=${field}]`).val();
        },

        Cerrar : function () {
            $(`#${this.IdModal}`).modal('hide');
            this.Limpiar();
        },

        getDatos: function () {
            var formElement = document.getElementById(this.IdFormulario);
            var formData = new FormData(formElement);

            return formData;
        },

        setDatos: function (d) {
            this.setCampo('id', d.id);
            this.setCampo('tipo', d.tipo);
            this.setCampo('serie', d.serie);
            this.setCampo('correlativo', d.correlativo);
        },

        fetchDatos: function(id) {
            var endpoint = `${base}sunat-fe-emisores/get-one-serie/${id}`;
            $.ajax({
                url: endpoint,
                data: {},
                type:'GET',
                success : function(r){
                    console.log(r);
                    if(r.success){
                        FormSeries.setDatos(r.data);
                        FormSeries.poblarEmisores(r.data.emisor_id);
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
            this.Limpiar();
            this.fetchDatos(id);

        },

        poblarAlmacenes: function (sel) {
            var endpoint = `${base}almacenes/get-all`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: function (r) {
                    var html = ``;
                    $(r.data).each(function (i, o) {
                        if (o.id == sel) {
                            html += `<option value="${o.id}" selected>${o.nombre}</option>`;
                        } else {
                            html += `<option value="${o.id}">${o.nombre}</option>`;
                        }

                    });

                    $('#' + FormSeries.IdFormulario + " [name=almacen_id]").html(html);
                }
            });
        },
        poblarEmisores: function (sel) {
            var endpoint = `${base}sunat-fe-emisores/get-all`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: function (r) {
                    var html = ``;
                    $(r.data).each(function (i, o) {
                        if (o.id == sel) {
                            html += `<option value="${o.id}" selected>${o.razon_social}</option>`
                        } else {
                            //html += `<option value="${o.id}">${o.razon_social}</option>`
                        }

                    });

                    $('#' + FormSeries.IdFormulario + " [name=emisor_id]").html(html);
                }
            });
        },

        Eliminar: function (id) {
            var confirmacion = confirm('Confirmar eliminación');
            if (confirmacion) {
                var endpoint = `${base}sunat-fe-emisores/del-serie`;
                var datos = {id: id};
                $.ajax({
                    url: endpoint,
                    data: datos,
                    type: 'POST',
                    success: function (r) {
                        FormSeries.guardar_callback();
                    }
                });
            }
        },

        Guardar: function (e) {
            e.preventDefault();
            var endpoint = `${base}sunat-fe-emisores/save-serie`;
            var datos = this.getDatos();

            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function (r) {
                    console.log(r);
                    if (r.success) {
                        FormSeries.guardar_callback();
                    } else {
                        alert("Error interno, consulte con un administrados")
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


