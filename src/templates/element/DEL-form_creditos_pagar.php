<div class="modal fade"
     id="formCreditosPagarModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formCreditosPagarModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formCreditosPagarModalLabel">Pagar Credito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formCreditosPagar" enctype="multipart/form-data" onsubmit="FormCreditosPagar.Guardar(event)">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="credito_id" value="">
                    <input type="hidden" name="estado" value="PAGADO">

                    <div class="row">
                        <div class="col">
                            <h4 id="message"></h4>
                        </div>
                    </div>

                    <br>

                    <div class="form-group row">
                        <div class="col">
                            <label>Monto:</label>
                            <input type="number" name="monto" class="form-control" autocomplete="off" readonly>
                        </div>
                        <div class="col">
                            <label>Fecha de Vencimiento:</label>
                            <input type="text" name="fecha_vencimiento" class="form-control" autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <label>Cuenta Bancaria:</label>
                            <select name="banco_cuenta_id" class="form-control">
                            </select>
                        </div>
                        <div class="col">
                            <label>Fecha de Pago:</label>
                            <input type="text" name="fecha_pago" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <label>Número de operación:</label>
                            <input type="text" name="operacion_numero" class="form-control" autocomplete="off">
                        </div>

                        <div class="col">
                            <label>Comprobante de Pago:</label>
                            <input type="file" name="ruta_adjunta" class="form-control" autocomplete="off" accept='image/x-png,image/gif,image/jpeg'>
                        </div>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Guardar Pago">

                    <input type="hidden" name="proyecto_id" value="<?= $proyecto->id ?>">
                    <input type="hidden" name="centro_costo_id" value="<?= $centro_costo_id ?>">
                </form>

            </div>
        </div>
    </div>
</div>


<script>
    var FormCreditosPagar = {
        guardar_callback: null,

        setEventoGuardar: function (fn) {
            this.guardar_callback = fn;
        },

        Limpiar: function () {
            this.setCampo('id', '');
            this.setCampo('credito_id', '');
            this.setCampo('ruta_adjunta', '');
            this.setCampo('monto', '');
            this.setCampo('fecha_vencimiento', '');
            this.setCampo('fecha_pago', '');
            this.setCampo('operacion_numero', '');
            $('#message').html('');
        },

        Nuevo: function (credito_id) {
            this.Limpiar();
            this.setCampo('credito_id', credito_id);
            $('#formCreditosPagarModal').modal('show');
            this.extraerCronograma(credito_id);
            this.poblarCuentas();
            $('#formCreditosPagar [name=fecha_pago]').datepicker({
                locale: 'es-es',
                format: 'yyyy-mm-dd',
                uiLibrary: 'bootstrap4'
            });
        },

        Cerrar: function () {
            $('#formCreditosPagarModal').modal('hide');
            this.Limpiar();
        },

        Guardar: function (e) {
            e.preventDefault();
            var datos = this.getDatos();
            var endpoint = `${base}credito-cronogramas/save`;

            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function (r) {
                    console.log(r);
                    FormCreditosPagar.guardar_callback();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },

        setCampo  : function(field, value){
            $("#formCreditosPagar [name="+field+"]").val(value);
        },
        getCampo  : function(field){
            return $("#formCreditosPagar [name="+field+"]").val();
        },

        getDatos: function () {
            var formElement = document.getElementById("formCreditosPagar");
            var formData = new FormData(formElement);

            return formData;
        },

        setDatos: function (d) {
            FormCreditosPagar.setCampo('id', d.id);
            FormCreditosPagar.setCampo('credito_id', d.credito_id);
            FormCreditosPagar.setCampo('monto', parseFloat(d.monto).toFixed(2));
            FormCreditosPagar.setCampo('fecha_vencimiento', d.fecha_vencimiento.substring(0, 10));
            var f = new Date();
            FormCreditosPagar.setCampo('fecha_pago', f.getFullYear() + "-" + (f.getMonth() +1).toString().padStart(2, 0) + "-" + f.getDate().toString().padStart(2, 0));
            FormCreditosPagar.setCampo('estado', 'PAGADO');
        },

        found: false,

        extraerCronograma: function (credito_id) {
            var endpoint = `${base}credito-cronogramas/get-all/${credito_id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    if (r.success) {
                        FormCreditosPagar.found = false;
                        for (var i = 0; i < r.data.length; i++) {
                            if (r.data[i].estado == "PENDIENTE") {
                                FormCreditosPagar.setDatos(r.data[i]);
                                FormCreditosPagar.found = true;
                                break;
                            }
                        }
                        if (!FormCreditosPagar.found) {
                            $('#message').html('No se encontraron cuotas por pagar');
                        } else {
                            $('#message').html('');
                        }
                    }
                }
            });
        },

        poblarCuentas: function (valor_por_defecto) {
            var endpoint = `${base}banco-cuentas/get-all`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    //console.log(r);
                    var datos = ``;
                    if (r.success) {
                        $(r.data).each((i, o) => {
                            if (o.id == valor_por_defecto) {
                                datos += `<option value="${o.id}" selected>${o.banco} - ${o.cuenta_numero} - ${o.moneda}</option>`;
                            } else {
                                datos += `<option value="${o.id}">${o.banco} - ${o.cuenta_numero} - ${o.moneda}</option>`;
                            }
                        });

                        $('#formCreditosPagar [name=banco_cuenta_id]').html(datos);
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
