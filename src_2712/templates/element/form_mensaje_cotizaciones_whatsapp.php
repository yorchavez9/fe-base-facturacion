
<div class="modal fade" id="whatsappVentasModal" tabindex="-1" role="dialog" aria-labelledby="whatsappVentasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header pb-2">
                <h5   style=" color: #000000; font-family: 'Lato', sans-serif; font-size: 20px; font-weight: 100; line-height: 20px;" id="whatsappVentasModalLabel">Mensaje Whatsapp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm  ">
                            <div class="input-group-prepend">
                                <label class="input-group-text"><i class="fas fa-user fa-fw" ></i></label>
                            </div>

                            <input type="text" name="clienteNombre" readonly class="form-control" placeholder="Nombre del Cliente" autocomplete="off">

                        </div>
                    </div>
                </div>

                <br>

                <div class="row mb-1">
                    <div class="col">
                        <label >Celular de Trabajo</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend ">
                                <label class="input-group-text"><i class="fas fa-user fa-fw" ></i></label>

                            </div>

                            <input type="text" id="inputClienteCelularTrabajo" class="form-control" placeholder="Número del Cliente" autocomplete="off">

                            <div class="input-group-append">
                                <button class="btn text-white" style="background-color: #158587" id="btnClienteCelularTrabajo" onclick="FormCotizacionesWhatsapp.EnviarCotizacion('inputClienteCelularTrabajo','btnClienteCelularTrabajo')">
                                    <i class="fa fa-paper-plane fa-fw"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="col-md-12" id="prueba_parientes_dinamicos">

                </div>

                <div class="col-md-12" style="display: none" >
                    <div class="row mb-3">
                        <div class="input-group input-group-sm  ">
                            <div class="input-group-prepend">
                                <label class="input-group-text"><i class="fas fa-user fa-fw" ></i></label>
                            </div>

                            <input type="text" name="ClienteCelularOtro" class="form-control" placeholder="Otro">

                            <div class="input-group-append">
                                <button class="btn text-white" style="background-color: #158587">
                                    Enviar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>




            </div>
            <div class="modal-footer justify-content-center">
                <!--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>-->
                <button type="button" class="btn  btn-success" style="display: none" >Aceptar</button>
                <button type="button" class="btn btn-danger"  data-dismiss="modal" style="display: none"  >Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>




    var FormCotizacionesWhatsapp={

        IdModal:"whatsappVentasModal",
        datos:[] ,
        cotizacion_id:"",


        NuevoMensaje: function(cotizacion_id,cliente_id,cliente_persona_tipo,cliente_razon_social){
            FormCotizacionesWhatsapp.Limpiar();
            FormCotizacionesWhatsapp.cotizacion_id = cotizacion_id;
            $(`#${this.IdModal}`).modal('show');
            $('[name=clienteNombre]').val(cliente_razon_social);
            $('#inputClienteCelular').mask('#');
            $('#inputClienteCelularTrabajo').mask('#');

            console.log(cliente_id);
            console.log(cliente_persona_tipo);
            console.log(cliente_razon_social);

            if(cliente_id != 0){

                if(cliente_persona_tipo == 'NATURAL'){
                    FormCotizacionesWhatsapp.RecuperarDatosPersona(cliente_id);
                }else if(cliente_persona_tipo == 'JURIDICA'){
                    FormCotizacionesWhatsapp.RecuperarDatosCuenta(cliente_id);
                }
            }


        },
        RecuperarDatosPersona(cliente_id){
            $.ajax({
                url:base+'personas/get-one/'+cliente_id,
                type:'GET',
                data:'',
                success:function(data){
                    if(data.success){
                        console.log(data.data);

                        FormCotizacionesWhatsapp.datos = data.data;
                        $('[name=clienteNombre]').val(data.data.nombres);
                        $('#inputClienteCelular').val(data.data.celular_personal).attr("readonly",false);
                        $('#inputClienteCelularTrabajo').val(data.data.celular_trabajo).attr("readonly",false);
                        $('#btnClienteCelularTrabajo').html("<i class='fa fa-paper-plane fa-fw'></i>");
                        $('#btnClienteCelular').html("<i class='fa fa-paper-plane fa-fw'></i>");

                    }else{
                        alert(data.message);
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }


            });


        },
        RecuperarDatosCuenta( cliente_id){
            $.ajax({
                url:base+'cuentas/get-one/'+cliente_id,
                type:'GET',
                data:'',
                success:function(data){
                    if(data.success){
                        console.log(data);
                        FormCotizacionesWhatsapp.datos = data.data;
                        $('[name=clienteNombre]').val(data.data.razon_social);
                        $('#inputClienteCelular').val(data.data.whatsapp).attr("readonly",false);
                        $('#inputClienteCelularTrabajo').val(data.data.celular).attr("readonly",false);
                        $('#btnClienteCelularTrabajo').html("<i class='fa fa-paper-plane fa-fw'></i>");
                        $('#btnClienteCelular').html("<i class='fa fa-paper-plane fa-fw'></i>");


                    }else{
                        alert(data.message);
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }


            });

        },
        Limpiar: function (){
            $('[name=clienteNombre]').val("");
            $('#inputClienteCelular').val("").attr("readonly",false);
            $('#inputClienteCelularTrabajo').val("").attr("readonly",false);
            $('#btnClienteCelularTrabajo').html("<i class='fa fa-paper-plane fa-fw'></i>");
            $('#btnClienteCelular').html("<i class='fa fa-paper-plane fa-fw'></i>");

        },

        EnviarCotizacion(input_id, btn_id  ){


            if($(`#${input_id}`).val().length == 0){
                alert('No se permiten numeros vacios');
                return;
            }


            $(`#${btn_id}`).html("<i class='spinner-border spinner-border-sm' style='display: inline-block'></i>");



            var endpoint = 'whatsapp/enviar-cotizacion/';

            var cliente_id = (this.datos == "" ? "":this.datos.id);
            var cotizacion_id     = this.cotizacion_id;
            var data = {
                "number"              :   $(`#${input_id}`).val(),
                "cliente_id"      :   cliente_id,
                "cotizacion_id"            :   cotizacion_id,
            };


            $.ajax({
                url : base + endpoint,
                type: 'GET',
                data: data,
                contentType: 'multipart/form-data',
                success:function (data){

                    console.log(data);

                    setTimeout(function(){$(`#${btn_id}`).html("<i class='fa fa-check fa-fw'></i>")},500);
                    $(`#${input_id}`).attr("readonly",true);


                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });



        },







    }





</script>

