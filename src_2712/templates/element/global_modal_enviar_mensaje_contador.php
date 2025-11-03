<div class="modal fade" id="modalEnviarMensajeContador" tab-index="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentacion_title">
                  <i class="fa fa-fw fa-thumbs-up" ></i>  Comunícate con tu contador
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" >
                    <div class="col-12" >
                        <div id="div_datos_contador" ></div>
                    </div>
                </div>
                <form id="formEnviarMensajeContador" class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-12 py-1">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-building fa-fw"></i>
                                    </span>
                                </div>
                                <input readonly name="user_nombre_empresa" class="form-control form-control-sm" type="text" placeholder="Nombre de tu empresa">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 pb-1">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-check fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="user_nombre" class="form-control form-control-sm" placeholder="Tu nombre" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 pb-1">

                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fab fa-whatsapp fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="user_celular" class="form-control form-control-sm" placeholder="Ejem : 51991658501">
                                <div class="invalid-feedback">
                                    Debe ingresar un celular válido
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <textarea name="user_mensaje" placeholder="Envíale algo a tu contador...." cols="30" class="form-control form-control-sm" rows="5">
                            </textarea>
                            <div class="invalid-feedback">
                                Es obligatorio especificar un mensaje
                            </div>
                        </div>
                        .
                    </div>
                    <div class="row">
                        <div class="col-12 my-1" id="div_mensaje_exito">
                            <div class="alert alert-success" role="alert">
                                Mensaje enviado satisfactoriamente!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 my-1" id="div_mensaje_error">
                            <div class="alert alert-danger" role="alert">
                                Ha habido un error!
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2 text-right">
                            <button class="btn btn-sm btn-outline-primary" type="submit">
                                <i class="fab fa-whatsapp fa-fw"></i> Enviar
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" data-dismiss="modal">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



<script>
    var GlobalModalEnviarMensajeContador = {
        idModal: "modalEnviarMensajeContador",
        idForm: "formEnviarMensajeContador",
        idDivDatosContador : "div_datos_contador",
        init: () => {
            $("[name=user_celular]").mask('000000000');
            $("body").keydown(function(e) {

                var keyCode = e.keyCode || e.which;
                if (keyCode == 117) {
                    e.preventDefault();
                    ModalDatosContador.abrir();
                }
            });
        },
        open: () => {
            GlobalModalEnviarMensajeContador.cleanForm();
            GlobalModalEnviarMensajeContador.setEmisor();
        },
        openModal: () => {
            $(`#${GlobalModalEnviarMensajeContador.idModal}`).modal('show');
        },
        sendMessage: () => {
            if (!GlobalModalEnviarMensajeContador.formIsValid()) {
                return;
            }

            GlobalSpinner.mostrar();
            const data = GlobalModalEnviarMensajeContador.generateForm();
            const endpoint = `${base}configuraciones/enviar-mensaje-contador`;
            $.ajax({
                url: endpoint,
                data: data,
                type: 'POST',
                success: (data) => {
                    if (data.success) {
                        GlobalModalEnviarMensajeContador.showDivExito();
                    } else {
                        GlobalModalEnviarMensajeContador.showDivError();
                    }
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    GlobalModalEnviarMensajeContador.showDivError();
                },
                complete: () => {
                    GlobalSpinner.ocultar();
                }
            });

        },
        formIsValid: () => {
            const message = GlobalModalEnviarMensajeContador.getFieldValue('user_mensaje');
            if (message != '') {
                GlobalModalEnviarMensajeContador.setValidField('user_mensaje');
            } else {
                GlobalModalEnviarMensajeContador.setInvalidField('user_mensaje');
                return false;
            }

            const celular = GlobalModalEnviarMensajeContador.getFieldValue('user_celular');
            if (celular.match(/^[0-9]{1,11}$/)) {
                GlobalModalEnviarMensajeContador.setValidField('user_celular');
            } else {
                GlobalModalEnviarMensajeContador.setInvalidField('user_celular');
                return false;
            }

            return true;

        },
        setEmisor: function() {
            var endpoint = `${base}api/get-support-info`;
            GlobalSpinner.mostrar();
            $.ajax({
                url: endpoint,
                data: {},
                success: function(data) {
                    GlobalModalEnviarMensajeContador.setFieldValue("user_nombre_empresa", data.razon_social + " - " + data.ruc);
                    GlobalModalEnviarMensajeContador.setFieldValue("user_nombre", data.user_nombre);
                    GlobalModalEnviarMensajeContador.getDatosContador();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // alert(xhr.status);
                    // alert(thrownError);
                    alert("Ha habido un error al abrir el formulario");
                    GlobalSpinner.ocultar();
                },
            })
        },
        getDatosContador : function(){
            const endpoint = `${base}configuraciones/consultar-datos-contador`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: (data) => {
                    if (data.success) {
                        GlobalModalEnviarMensajeContador.setDatosContador(data.data);
                        GlobalModalEnviarMensajeContador.openModal();
                    } else {
                        alert('Ha habido un error para consultar los datos del contador');
                    }
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    console.log(xhr.status);
                    console.log(thrownError);
                    alert("Ha habido un error para cargar los datos");
                },
                complete : ()   =>  {
                    GlobalSpinner.ocultar();
                }
            })
        },
        setDatosContador :  (data)  =>  {
            let nombre      = data.nombre; 
            let ruta_foto   = data.ruta_foto != '' ? `${base_root}${data.ruta_foto}` : 'http://via.placeholder.com/256x25';;
            let telefono    = data.telefono;
            let correo      = data.correo;

            const html = ` 
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 text-center" >
                    
                        <img width="128px" src="${ruta_foto}" class="img-fluid" />
                    
                    </div>
                    <div class="col m-auto" >
                        <div class="form-row" >
                            <div class="col-12">
                            <h4 class="text-center" >Datos de tu contador</h4> 
                            </div> 
                            <div class="col-12 p-1">
                                <div class="input-group input-group-sm" >
                                    <div class="input-group-prepend" >
                                        <div class="input-group-text" >
                                            <i class="fa fa-fw fa-user" ></i>
                                        </div>
                                    </div>
                                    <input class="form-control form-control-sm" value="${data.nombre}" readonly />
                                </div>
                            </div>
                            <div class="col p-1">
                                <div class="input-group input-group-sm" >
                                    <div class="input-group-prepend" >
                                        <div class="input-group-text" >
                                            <i class="fa fa-fw fa-envelope" ></i>
                                        </div>
                                    </div>
                                    <input class="form-control form-control-sm" value="${data.correo}" readonly />
                                </div>
                            </div>
                            <div class="col p-1">
                                <div class="input-group input-group-sm" >
                                    <div class="input-group-prepend" >
                                        <div class="input-group-text" >
                                            <i class="fa fa-fw fa-phone" ></i>
                                        </div>
                                    </div>
                                    <input class="form-control form-control-sm" value="${data.telefono}" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
            
            
            
            `;

            $(`#${GlobalModalEnviarMensajeContador.idDivDatosContador}`).html(html);




        },
        generateForm: () => {
            var form = $(`#${GlobalModalEnviarMensajeContador.idForm}`).serializeObject();
            return form;
        },
        closeModal: () => {
            $(`#${GlobalModalEnviarMensajeContador.idModal}`).modal('hide');
        },
        cleanForm: () => {
            $(`#${GlobalModalEnviarMensajeContador.idModal} #div_mensaje_exito`).hide();
            $(`#${GlobalModalEnviarMensajeContador.idModal} #div_mensaje_error`).hide();

            GlobalModalEnviarMensajeContador.setFieldValue('user_nombre_empresa', '');
            GlobalModalEnviarMensajeContador.setFieldValue('user_nombre', '');
            GlobalModalEnviarMensajeContador.setFieldValue('user_celular', '');
            GlobalModalEnviarMensajeContador.setFieldValue('user_mensaje', '');

            $("#div_datos_contador").html("");

            GlobalModalEnviarMensajeContador.cleanValidations('user_mensaje');
            GlobalModalEnviarMensajeContador.cleanValidations('user_celular');


        },
        getFieldValue: (field_name) => {
            return $(`#${GlobalModalEnviarMensajeContador.idModal} [name=${field_name}]`).val();
        },
        setFieldValue: (field_name, new_value) => {
            return $(`#${GlobalModalEnviarMensajeContador.idModal} [name=${field_name}]`).val(new_value);
        },
        setValidField: (field_name) => {
            $(`[name=${field_name}]`).removeClass('is-invalid');
            $(`[name=${field_name}]`).addClass('is-valid');

        },
        setInvalidField: (field_name) => {
            $(`[name=${field_name}]`).removeClass('is-valid');
            $(`[name=${field_name}]`).addClass('is-invalid');
        },
        cleanValidations: (field_name) => {
            $(`[name=${field_name}]`).removeClass('is-valid');
            $(`[name=${field_name}]`).removeClass('is-invalid');
        },
        showDivExito: () => {
            $(`#${GlobalModalEnviarMensajeContador.idModal} #div_mensaje_exito`).show();
            $(`#${GlobalModalEnviarMensajeContador.idModal} #div_mensaje_error`).hide();
        },
        showDivError: () => {
            $(`#${GlobalModalEnviarMensajeContador.idModal} #div_mensaje_exito`).hide();
            $(`#${GlobalModalEnviarMensajeContador.idModal} #div_mensaje_error`).show();
        }

    }

    window.addEventListener('load', () => {
        const form = document.getElementById(GlobalModalEnviarMensajeContador.idForm);
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            GlobalModalEnviarMensajeContador.sendMessage();
        })
    })
</script>