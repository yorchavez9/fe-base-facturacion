<div class="modal fade " id="modalMensajePersonaWhatsapp" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" > <i class="fab fa-whatsapp fa-fw"></i> Enviar Whatsapp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user-check fa-fw"></i>
                            </span>
                            </div>
                            <input type="text" readonly class="form-control" placeholder="Persona" name="persona_nombre" >
                        </div>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-phone fa-fw" ></i>
                            </span>
                            </div>
                            <input type="text" readonly  class="form-control" name="persona_celular" placeholder="Numero">
                        </div>
                    </div>
                    <div class="col-sm-12 mt-2">
                        <textarea name="mensaje" id="" cols="30" rows="10" class="form-control form-control-sm" placeholder="Ingrese su mensaje"></textarea>
                    </div>
                    <div class="col-sm-12 mt-2">
                        <button class="btn btn-success rounded-pill w-100  " id="btnEnviarWhatsapp" onclick="ModalMensajePersonaWhatsapp.enviarMensaje()" > <i class="fa fa-paper-plane fa-fw" id="iconEnviarWhatsapp" ></i> Enviar </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    ModalMensajePersonaWhatsapp={
        IdModal: 'modalMensajePersonaWhatsapp',
        telefonoCliente: 0,
        nuevoMensaje: function(persona_nombre,persona_celular){
            $(`#${this.IdModal}`).modal('show');
            $(`#${this.IdModal} [name=persona_nombre]`).val(persona_nombre);
            $(`#${this.IdModal} [name=persona_celular]`).val(persona_celular);
        },
        enviarMensaje:function(){
            var number = $(`#${this.IdModal} [name=persona_celular]`).val(),
                message = $(`#${this.IdModal} [name=mensaje]`).val(),
                data = {number: number, message: message },
                endpoint = `${base}whatsapp/send-msg`;

            ModalMensajePersonaWhatsapp.setEnviando();

            $.ajax({
                url: endpoint,
                data: data,
                type: 'POST',
                success: function(data){
                    if(data.success){
                        $(`#${ModalMensajePersonaWhatsapp.IdModal} [name=mensaje]`).val('');
                        ModalMensajePersonaWhatsapp.setEnviado();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    // alert(xhr.status);
                    // alert(thrownError);
                    alert('Ha habido un error en el envío del mensaje');
                }

            })
        },
        setEnviando:function(){
            $(`#${this.IdModal} #btnEnviarWhatsapp`).prop('disabled',true);
            $(`#${this.IdModal} #iconEnviarWhatsapp`).removeClass('fa-paper-plane');
            $(`#${this.IdModal} #iconEnviarWhatsapp`).addClass('spinner-border');
            $(`#${this.IdModal} #iconEnviarWhatsapp`).addClass('spinner-border-sm');
        },
        setEnviado: function (){

            $(`#${this.IdModal} #btnEnviarWhatsapp`).prop('disabled',false);
            $(`#${this.IdModal} #iconEnviarWhatsapp`).removeClass('spinner-border');
            $(`#${this.IdModal} #iconEnviarWhatsapp`).removeClass('spinner-border-sm');
            $(`#${this.IdModal} #iconEnviarWhatsapp`).addClass('fa-paper-plane');

        },
        setCampo  : function(field, value){
            $(`#${this.IdModal} [name=${field}]` ).val(value);
        },
        getCampo  : function(field){
            return $(`#${this.IdModal} [name=${field}]` ).val();
        },
    }

</script>
