<div class="modal fade"
     id="formFactoringModal"
     tabindex="-1" role="dialog"
     aria-labelledby="factoringModalLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="factoringModalLabel">Factoring</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formFactoring" enctype="multipart/form-data" onsubmit="FormFactoring.Guardar(event)">

                    <div class="row">
                        <div class="col">
                            <label for="entidad">Entidad de Factoring</label>
                            <input type="text" id="entidad" name="entidad" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col">
                            <label for="banco_cuenta_id">Cuenta Bancaria a la cual abonan</label>
                            <select name="banco_cuenta_id" id="banco_cuenta_id" class="custom-select col md">
                            </select>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col">
                            <label>Moneda y Monto</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select name="moneda" id="moneda" class="input-group-text">
                                        <option value="PEN">S/.</option>
                                        <option value="USD">$.</option>
                                    </select>

                                </div>

                                <input type="text" id="monto" name="monto" class="form-control" placeholder="monto" autocomplete="off">
                            </div>

                        </div>
                        <div class="col">
                            <label for="fecha_pago_cuenta">Fecha de Pago de Cuenta:</label>
                            <input type="text" id="fecha_pago_cuenta" name="fecha_pago_cuenta" class="form-control" value='<?= date("Y-m-d") ?>'>
                        </div>


                    </div>
                    <br>

                    <div class="row">
                        <div class="col">
                            <label>Comisión %:</label>
                            <input type="number" step="any" name="comision" class="form-control" autocomplete="off">
                        </div>

                        <div class="col">
                            <label>Número de operación:</label>
                            <input type="text" name="operacion_numero" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <br>
                    <label for="condiciones_comerciales">Condiciones</label>
                    <textarea name="condiciones_comerciales" id="condiciones_comerciales" class="form-control"></textarea>
                    <br>
                    <label for="">Comprobante de la transacción</label>
                    <input type="file" class="form-control col" name="ruta_adjunto" accept="image/jpeg" />
                    <br>
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="proyecto_id" value="<?= $proyecto->id ?>">
                    <input type="hidden" name="centro_costo_id" value="<?= $centro_costo_id ?>">
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var FormFactoring = {
        guardar_callback: null,
        setEventoGuardar: function (fn) {
            this.guardar_callback = fn;
        },

        calcularMonto: function () {
            var endpoint = `${base}centro-costos/get-saldos/${centro_costo_id}`;
            $.ajax({
                url: endpoint,
                data: '',
                type: 'GET',
                success: function (r) {
                    if (r.success) {
                        FormFactoring.setCampo('moneda', r.data.moneda)
                        var calculo = r.data.presupuesto-r.data.liquidez;
                        FormFactoring.setCampo('monto', calculo.toFixed(2))
                    }
                }
            });
        },

        Nuevo: function () {
            $('#formFactoringModal').modal('show');
            this.Limpiar();
            this.poblarCuentas(0);

            $('#formFactoring [name=fecha_pago_cuenta]').datepicker({
                locale: 'es-es',
                format: 'yyyy-mm-dd',
                uiLibrary: 'bootstrap4'
            });

            this.calcularMonto();
        },

        Limpiar: function () {
            $('#formFactoring [name=banco_cuenta_id]').val('');
            $('#formFactoring [name=entidad]').val('');
            $('#formFactoring [name=moneda]').val('PEN');
            $('#formFactoring [name=monto]').val('');
            //$('#formFactoring [name=fecha_pago_cuenta]').val('');
            $('#formFactoring [name=condiciones_comerciales]').val('');
            $('#formFactoring [name=ruta_adjunto]').val('');
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

                        $('#formFactoring [name=banco_cuenta_id]').html(datos);
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

        },

        Guardar: function(e) {
            e.preventDefault();
            var datos = FormFactoring.getDatos();
            var endpoint = `${base}factoring/save`;

            $.ajax({
                url: endpoint,
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(r) {
                    console.log(r);
                    FormFactoring.guardar_callback();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },

        setCampo  : function(field, value){
            $("#formFactoring [name="+field+"]").val(value);
        },
        getCampo  : function(field){
            return $("#formFactoring [name="+field+"]").val();
        },

        getDatos: function () {
            var formElement = document.getElementById("formFactoring");
            var formData = new FormData(formElement);

            return formData;
        },

        Cerrar : function () {
            $('#formFactoringModal').modal('hide');
        },

        setDatos : function (d) {
            FormFactoring.poblarCuentas(0);
            $('#formFactoring [name=id]').val(d.id);
            $('#formFactoring [name=banco_cuenta_id]').val(d.banco_cuenta_id);
            $('#formFactoring [name=entidad]').val(d.entidad);
            $('#formFactoring [name=moneda]').val(d.moneda);
            $('#formFactoring [name=monto]').val(d.monto);
            $('#formFactoring [name=fecha_pago_cuenta]').val(d.fecha_pago_cuenta);
            $('#formFactoring [name=condiciones_comerciales]').val(d.condiciones_comerciales);
            $('#formFactoring [name=ruta_adjunto]').val(d.ruta_adjunto);
        },
        fetchDatos: function(id) {
            var endpoint = `${base}factoring/get-one/${id}`;
            $.ajax({
                url: endpoint,
                data: {},
                type:'GET',
                success : function(r){
                    if(r.success){
                        FormFactoring.setDatos(r.data);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });
        },

        Editar: function (id) {
            $("#formCajaChicaModal").modal('show')
            FormCajaChica.fetchDatos(id);
        }
    };

</script>
