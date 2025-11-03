<div class="modal fade" id="creditosModal" tabindex="-1" role="dialog" aria-labelledby="creditosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="creditosModalLabel">Nuevo Crédito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <form id="formCreditos" onsubmit="FormCreditos.Guardar(event)">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="entidad">Entidad que presta el dinero</label>
                                <input type="text" id="entidad" name="entidad" class="form-control" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="col">
                            <label for="">Cargar crédito en</label>
                            <select id="fcred_banco_cuenta_id" name="banco_cuenta_id" class="custom-select" onchange="FormCreditos.ActualizarMoneda()"></select>
                        </div>

                        <div class="col">
                            <label>Número de operación:</label>
                            <input type="text" name="operacion_numero" class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class=" row">

                        <div class="col">
                            <label>Moneda</label>
                            <select name="moneda" class="form-control">
                                <option value="PEN">Soles</option>
                                <option value="USD">Dólares</option>
                            </select>
                        </div>

                        <div class="col">
                            <label for="monto">Monto de Préstamo</label>
                            <input type="number" step="any" onchange="FormCreditos.generarCronogramaPagos()" id="monto" name="monto" class="form-control" autocomplete="off" required>
                        </div>

                        <div class="col">
                            <label for="numero_cuotas">Cantidad de Cuotas a Pagar</label>
                            <input type="number" onchange="FormCreditos.generarCronogramaPagos()" id="numero_cuotas" name="numero_cuotas" class="form-control" autocomplete="off" required>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <table id="formCreditoCronogramaTable" class="table">

                        </table>
                    </div>
                    <br>

                    <input type="hidden" name="id">
                    <input type="hidden" name="proyecto_id" value="<?= $proyecto->id ?? 0 ?>">
                    <input type="hidden" name="centro_costo_id" value="<?= $centro_costo_id ?? 0 ?>">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var FormCreditos = {
        ActualizarMoneda: function () {
            var combo = document.getElementById('fcred_banco_cuenta_id');
            var txt = combo.options[combo.selectedIndex].text;
            $('#formCreditos [name=moneda]').val(txt.substring(txt.length-3))

        },

        guardar_callback: null,

        setEventoGuardar: function (fn) {
            this.guardar_callback = fn;
        },

        Init: function (){
            $("#formCreditos [name=monto]").mask('0000000000000000000000000000000000.00', {reverse: true});
        },

        Nuevo: function () {
            $('#creditosModal').modal('show');
            FormCreditos.Limpiar();
            FormCreditos.poblarCuentas(0);

        },

        generarCronogramaPagos: function(){
            var nrocuotas = $("#formCreditos [name=numero_cuotas]").val();
            var monto_credito = $("#formCreditos [name=monto]").val();
            var cuota_mes_estimado = parseFloat(monto_credito) / parseFloat(nrocuotas);

            var html = ``;
            var h = new Date();
            for(var i = 1; i <= nrocuotas; i++){
                var fechapago = new Date(h.getFullYear(), h.getMonth()+i, h.getDate());
                var strFecha = fechapago.getFullYear() +
                    '-' + (fechapago.getMonth() + 1).toString().padStart(2, 0) +
                    '-' + fechapago.getDate().toString().padStart(2, 0);
                html += `
                <tr>
                    <td>Cuota # ${i} </td>
                    <td><input name='cuotas[${i}]' type='number' step="any" id="formCreditoCuota_${i}" value='${cuota_mes_estimado}' class='form-control' autocomplete="off" /></td>
                    <td><input name='fechas[${i}]' type='text' id='formCreditoCronoDate_${i}' value='${strFecha}' class='form-control' /></td>
                </tr>
            `;
            }
            $("#formCreditoCronogramaTable").html(html);

            for(var i = 1; i <= nrocuotas; i++){
                // generamos el datepicker
                $("#formCreditoCronoDate_" + i).datepicker({
                    locale: 'es-es',
                    format: 'yyyy-mm-dd',
                    uiLibrary: 'bootstrap4'
                });

                var v = $('#formCreditoCuota_' + i).val();
                $('#formCreditoCuota_' + i).val(Math.round(v*100)/100)
            }

        },

        Limpiar: function () {
            $('#formCreditos [name=id]').val('');
            $('#formCreditos [name=entidad]').val('');
            $('#formCreditos [name=moneda]').val('');
            $('#formCreditos [name=monto]').val('');
            $('#formCreditos [name=numero_cuotas]').val('');
            $('#formCreditos [name=banco_cuenta_id]').val('');
            $("#formCreditoCronogramaTable").html('');
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

                        $('#formCreditos [name=banco_cuenta_id]').html(datos);
                        FormCreditos.ActualizarMoneda();
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
            var datos = FormCreditos.getDatos();
            var endpoint = `${base}creditos/save`;

            $.ajax({
                url: endpoint,
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(r) {
                    console.log(r);
                    FormCreditos.guardar_callback();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },

        setCampo  : function(field, value){
            $("#formCreditos [name="+field+"]").val(value);
        },
        getCampo  : function(field){
            return $("#formCreditos [name="+field+"]").val();
        },

        getDatos: function () {
            var formElement = document.getElementById("formCreditos");
            var formData = new FormData(formElement);

            return formData;
        },

        Cerrar : function () {
            $('#creditosModal').modal('hide');
        },

        setDatos : function (d) {
            FormCreditos.poblarCuentas(0);
            $('#formCreditos [name=id]').val(d.id);
            $('#formCreditos [name=entidad]').val(d.entidad);
            $('#formCreditos [name=moneda]').val(d.moneda);
            $('#formCreditos [name=monto]').val(d.monto);
            $('#formCreditos [name=numero_cuotas]').val(d.numero_cuotas);
            $('#formCreditos [name=banco_cuenta_id]').val(d.cuenta_banco_transaccion_id);
        },
        fetchDatos: function(id) {
            var endpoint = `${base}creditos/get-one/${id}`;
            $.ajax({
                url: endpoint,
                data: {},
                type:'GET',
                success : function(r){
                    if(r.success){
                        FormCreditos.setDatos(r.data);
                        FormCreditos.generarCronogramaPagos();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });
        },

        Editar: function (id) {
            this.setEventoGuardar(function () {
                this.Cerrar();
                location.reload();
            });
            $("#creditosModal").modal('show')
            this.fetchDatos(id);
        }
    };
</script>
