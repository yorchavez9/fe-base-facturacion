<div class="modal fade" id="ModalUsuarioPerfiles" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalPermisosUsuariosLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  id="formGuardarPerfilUsuario" onsubmit="ModalUsuarioPerfiles.guardar(event)">
                    <input type="text" hidden name="usuario_id" id="usuario_id">
                    <ul class="list-unstyled" id="contenedorPerfiles">
                    </ul>
                </form>

            </div>
        </div>
    </div>
</div>


<script>
    var ModalUsuarioPerfiles =
        {
            modalId: 'ModalUsuarioPerfiles',
            Abrir(usuario_id){
                $(`#${this.modalId}`).modal('show');
                this.limpiarForm();
                this.getUsuario(usuario_id);

            },
            limpiarForm(){
                $('#contenedorPerfiles').html('');
            },
            getUsuario(usuario_id){
                var endpoint = `${base}usuarios/get-one/${usuario_id}`;
                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function(data){
                        console.log(data);
                        if(data.success){
                            $(`#${ModalUsuarioPerfiles.modalId} #modalPermisosUsuariosLabel`).html(`Perfil de ${data.data.nombre}`);
                            $(`#${ModalUsuarioPerfiles.modalId} [name=usuario_id]`).val(`${data.data.id}`);
                            ModalUsuarioPerfiles.poblarPerfiles(data.data.perfil_id);
                        }else{
                            alert('Ha habido un error en recuperar los datos');
                        }
                    },
                    error : function(xhr, ajaxOptions,thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    }
                })
            },
            poblarPerfiles(perfil_id){
                var endpoint = `${base}usuarios/get-all-perfiles/`;

                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function(data){
                        $('#contenedorPerfiles').html('');
                        console.log(data);
                        if(data.success){
                            var html = "";
                            for(let i = 0; i < data.data.length;i++ )
                            {
                                var checked = (data.data[i].id === perfil_id ? 'checked' : "");
                                html +=
                                    `
                                    <li>
                                        <label>
                                          <input type="radio" name="perfil_id" value="${data.data[i].id}" ${checked}   >
                                            ${data.data[i].nombre}
                                        </label>
                                    </li>
                                    `
                            }
                            html +=
                                `
                                <button type="submit" class="btn btn-sm btn-primary"  >
                                    <i class="fa fa-save fa-fw" id="iconGuardarPefilUsuario" ></i> Guardar
                                </button>
                                `
                            ;
                            $('#contenedorPerfiles').html(html);
                        }
                    },
                    error : function(xhr, ajaxOptions,thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    }
                })

            },
            guardar(e){
                e.preventDefault();
                var endpoint = `${base}usuarios/save-perfil-usuario/`;

                $(`#${ModalUsuarioPerfiles.modalId} #iconGuardarPefilUsuario`).removeClass('fa-save');
                $(`#${ModalUsuarioPerfiles.modalId} #iconGuardarPefilUsuario`).addClass('spinner-border');
                $(`#${ModalUsuarioPerfiles.modalId} #iconGuardarPefilUsuario`).addClass('spinner-border-sm');
                var form = new FormData(document.getElementById('formGuardarPerfilUsuario'));

                $.ajax({
                    url: endpoint,
                    data: form,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    success: function(data){
                        console.log(data);
                        if(data.success){
                            console.log(data);
                            alert('Datos registrados exitosamente');
                            $(`#${ModalUsuarioPerfiles.modalId} #iconGuardarPefilUsuario`).removeClass('spinner-border');
                            $(`#${ModalUsuarioPerfiles.modalId} #iconGuardarPefilUsuario`).removeClass('spinner-border-sm');
                            $(`#${ModalUsuarioPerfiles.modalId} #iconGuardarPefilUsuario`).addClass('fa-save');

                        }else{
                            alert('Ha habido un error en actualizar los datos');
                            // location.reload();
                        }
                    },
                    error : function(xhr, ajaxOptions,thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    }
                })

            }




        }


</script>
