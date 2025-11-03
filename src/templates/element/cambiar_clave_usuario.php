<div class="modal fade"
     id="formClaveUsuarioModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formClaveUsuarioLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formUsuariosLabel">Cambiar de clave para: <label for="" id="usuario_label"></label> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formClaveUsuarios" onsubmit="CambioClave.Guardar(event)">
                    <div class="py-2">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-key fa-fw"></i>
                                </span>
                            </div>
                            <input type="text" name="clave" class="form-control form-control-sm" autocomplete="off" placeholder="Contraseña">
                            <div class="input-group-append">
                                <button class="btn btn-primary" title="Generar clave" type="button" onclick="CambioClave.generarClave()"> <i class="fas fa-sync fa-fw"></i> </button>
                            </div>
                        </div>
                    </div>
                    <div class="py-2">
                        <div class="form-check check-group">
                            <input class="form-check-input" type="checkbox" value="1" name="enviar_correo" id="enviar_correo" autocomplete="off">
                            <label class="form-check-label" for="enviar_correo">
                                Enviar clave al correo del usuario.
                            </label>
                        </div>
                    </div>
                    <div class=" py-2">
                        <button type="submit" class="btn btn-sm btn-primary" id="btn_save"><i class="fa fa-save fa-fw"></i> Actualizar</button>
                    </div>
                    <input type="hidden" name="id" >
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var CambioClave = {
        IdFormulario: 'formClaveUsuarios',
        IdModal: 'formClaveUsuarioModal',
        Nuevo: function (id='', usuario='') {
            $(`#${CambioClave.IdModal}`).modal('show');
            CambioClave.Limpiar();
            CambioClave.setCampo('id', id);
            $("#usuario_label").html(usuario);
        },
        Limpiar: function () {
            CambioClave.setCampo('id', '');
            CambioClave.setCampo('clave', '');
            $("#usuario_label").html('');
        },
        setCampo  : function(field, value){
            $(`#${CambioClave.IdFormulario} [name=${field}]`).val(value);
        },
        getDatos: function () {
            var formElement = document.getElementById(CambioClave.IdFormulario);
            var formData = new FormData(formElement);
            return formData;
        },
        Cerrar : function () {
            $(`#${CambioClave.IdModal}`).modal('hide');
            CambioClave.Limpiar();
        },
        Guardar: function (e) {
            e.preventDefault();
            var endpoint = `${base}usuarios/api-actualizar-clave`;
            var datos = CambioClave.getDatos();
            $(`#${CambioClave.IdModal} #btn_save`).prop('disabled', true);
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
                        CambioClave.Cerrar();
                        alert(r.message);
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function () {
                    $(`#${CambioClave.IdModal} #btn_save`).prop('disabled', false);
                }
            });
        },
        generarClave : function () {
            var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ2346789@$";
            var pwd = "";
            for (i=0; i<8; i++) pwd += caracteres.charAt(Math.floor(Math.random()*caracteres.length));
            $(`#${CambioClave.IdFormulario} [name=clave]`).val(pwd);
        }

    };
</script>





