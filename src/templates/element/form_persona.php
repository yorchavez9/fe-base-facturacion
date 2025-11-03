<style type="text/css">
    #formPersonaModal .input-group-text { width: 42px; display: block; text-align: center; }

</style>
<div class="modal fade" id="formPersonaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Persona</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPersona" onsubmit="FormPersona.Guardar(event)">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">DNI</span>
                                </div>
                                <?= $this->Form->control('dni',['style' => 'width:140px', 'label' => false, 'maxlength'=> 8, 'class' => 'form-control form-control-sm ruc','placeholder' => 'Ingrese DNI','required' => false, 'autocomplete' => 'off']) ?>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" onclick="FormPersona.consultaDni()">
                                        <span class="estado_busqueda1">
                                            <i class="fas fa-exchange-alt fa-fw"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="div_cargo">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-building fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control letras form-control-sm" name="cargo_empresa" autocomplete="off"
                                   placeholder="Cargo en la Empresa"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="nombres" class="form-control letras" placeholder="Nombres"
                                       autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="apellidos" class="form-control letras" placeholder="Apellidos" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="domicilio" placeholder="Dirección" class="form-control letras" autocomplete="off" />
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope-open fa-fw"></i>
                                    </span>
                                </div>
                                <input type="email" name="correo" class="form-control email" placeholder="Correo Electrónico" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control form-control-sm" name="fecha_nacimiento" readonly data-toggle="tooltip" data-placement="top" title="Fecha de Nacimiento" value="<?= date('Y-m-d') ?>" >
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-mobile-alt fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="celular_trabajo" class="form-control celular"
                                   placeholder="Cel. Trabajo" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-mobile-alt fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="celular_personal" class="form-control celular"
                                   placeholder="Cel.Personal" autocomplete="off"/>
                                <input type="hidden" name="index"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="telefono" class="form-control telefono" placeholder="Teléfono" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fab fa-whatsapp fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="whatsapp" class="form-control celular"
                                   placeholder="Cod País + Whatsapp" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group text-right">
                                <input type="hidden" name="index" />
                                <input type="hidden" name="id" />
                                <input type="hidden" name="cuenta_id" />
                                <input type="hidden" name="empresa_id" />
                                <button type="submit" class="btn btn-primary btn-sm" id="formPersonaModalButton"><i class="fa fa-save fa-fw"></i> Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var FormPersona = {
        Comportamiento : "",
        Init: function (comportamiento) {
            /*
             * Comportamiento es si va a enviar datos al endpoint
             * o si va a retornar un json
             */

            FormPersona.Comportamiento = comportamiento;
            $(`[name=dni]`).mask("00000000");
            $(`[name=whatsapp]`).mask("000000000000");
            $(`[name=telefono]`).mask("000000000000");
            $(`[name=celular_personal]`).mask("000000000000");
            $(`[name=celular_trabajo]`).mask("000000000000");
            $(`[name=fecha_nacimiento]`).datepicker({
                locale: 'es-es',
                format: 'yyyy-mm-dd',
                uiLibrary: 'bootstrap4'
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });

        },

        callBack : null,

        Nuevo: function () {
            $('#formPersonaModal').modal('show');
            FormPersona.Limpiar();
        },

        NuevoParaCuenta: function (cuenta_id){
            FormPersona.setCampo("cuenta_id", cuenta_id);
            FormPersona.Abrir();
        },

        NuevoParaDirectorio: function (empresa_id){
            FormPersona.setCampo("empresa_id", empresa_id);
            FormPersona.Abrir();
        },

        Abrir   : function (){
            $('#formPersonaModal').modal('show');
        },

        Editar: function(id){
            $('#formPersonaModal').modal('show');
            FormPersona.Fetch(id);

        },

        Cerrar: function(){
            $('#formPersonaModal').modal('hide');
        },

        Guardar: function(e){
            e.preventDefault();

            $("#formPersonaModalButton").html("<i class='fas fa-spin fa-spinner fa-fw'></i> Guardando ...")
            $("#formPersonaModalButton").attr("disabled", true)

            var datos = this.getDatos();

            if(FormPersona.Comportamiento == 'get-json'){
                $("#formPersonaModalButton").html("<i class='fas fa-save fa-fw'></i> Guardar")
                $("#formPersonaModalButton").attr("disabled", false)
                FormPersona.callBack(datos);
            }else{
                var endpoint = `${base}personas/save`;
                $.ajax({
                    url: endpoint,
                    data: datos,
                    type: 'POST',
                    success: function (r) {
                        $("#formPersonaModalButton").html("<i class='fas fa-save fa-fw'></i> Guardar")
                        $("#formPersonaModalButton").attr("disabled", false)

                        FormPersona.callBack(r);
                        if (r.success) {

                        }
                    }
                });
            }
        },

        consultaDni : function(){
            var dni = $('#formPersona input[name=dni]').val();
            $("#formPersona .estado_busqueda1").html("<i class='fa fa-spinner fa-spin fa-fw'></i>");

            $.ajax({
                url: base + "sunat-fe/api-consulta-dni-ruc",
                data: {'documento': dni},
                type: 'GET',
                dataType: 'JSON',
                success: function (data, status, xhr) {// success callback function
                    console.log(data)
                    $('#formPersona input[name=dni]').val(dni);
                    $('#formPersona input[name=nombres]').val(data.result.nombres);
                    $('#formPersona input[name=apellidos]').val(data.result.apellidos);
                    $("#formPersona .estado_busqueda1").html("<i class='fa fa-check fa-fw'></i>");
                }, error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                }
            });

        },

        setCampo  : function(field, value){
            $("#formPersona [name="+field+"]").val(value);
        },
        getCampo  : function(field){
            return $("#formPersona [name="+field+"]").val();
        },


        getDatos: function () {
            var datos = $("#formPersona").serializeObject();
            return datos;
        },

        setDatos    : function(data){ // usar la funcion setCampo
            $("#formPersona [name=dni]").val(data.dni);
            $("#formPersona [name=cargo_empresa]").val(data.cargo_empresa);
            $("#formPersona [name=nombres]").val(data.nombres);
            $("#formPersona [name=apellidos]").val(data.apellidos);
            $("#formPersona [name=correo]").val(data.correo);
            $("#formPersona [name=telefono]").val(data.telefono);
            $("#formPersona [name=anexo]").val(data.anexo);
            $("#formPersona [name=celular_trabajo]").val(data.celular_trabajo);
            $("#formPersona [name=celular_personal]").val(data.celular_personal);
            $("#formPersona [name=id]").val(data.id);
            $("#formPersona [name=whatsapp]").val(data.whatsapp);
            $("#formPersona [name=domicilio]").val(data.domicilio);

            let date = new Date();
            let fecha_actual = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0') ;
            $("#formPersona [name=fecha_nacimiento]").val(data.fecha_nacimiento != null ? data.fecha_nacimiento.substr(0,10) : fecha_actual);
        },

        Limpiar     : function(){
            $("#formPersona .estado_busqueda1").html("<i class='fas fa-exchange-alt fa-fw'></i>");

            $("#formPersona [name=dni]").val("");
            $("#formPersona [name=cargo_empresa]").val("");
            $("#formPersona [name=nombres]").val("");
            $("#formPersona [name=apellidos]").val("");
            $("#formPersona [name=correo]").val("");
            $("#formPersona [name=telefono]").val("");
            $("#formPersona [name=anexo]").val("");
            $("#formPersona [name=celular_trabajo]").val("");
            $("#formPersona [name=celular_personal]").val("");
            $("#formPersona [name=id]").val("");
            $("#formPersona [name=whatsapp]").val("");
            $("#formPersona [name=domicilio]").val("");

            $("#formPersona [name=dni]").focus();
        },

        Fetch: function(id){
            $.ajax({
                url: base + "personas/get-one/" + id,
                data: {},
                type: 'GET',
                dataType: 'JSON',
                success: function (data, status, xhr) {// success callback function
                    console.log(data)
                    FormPersona.setDatos(data.data);

                }, error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                }
            });
        }
    };
</script>

