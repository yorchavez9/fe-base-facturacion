<div class="modal fade" id="ModalPermisoPerfiles" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalPermisosPerfilesLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="contenedorPermisos">
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var ModalPermisoPerfiles =
        {
            modalId: 'ModalPermisoPerfiles',
            abrir(perfil_id,perfil_nombre){
                $(`#${this.modalId}`).modal('show');
                $(`#${this.modalId} #modalPermisosPerfilesLabel`).html(`Permisos de ${perfil_nombre}`);
                this.filtrarPermisosPerfil(perfil_id);
            },
            filtrarPermisosPerfil(perfil_id){
                var endpoint = `${base}usuarios/get-permisos-perfil/${perfil_id}/all`;

                $.ajax({
                    url: endpoint,
                    data: '',
                    type: 'GET',
                    success: function(data){
                        $('#contenedorPermisos').html('');
                        console.log(data)
                        if(data.success){
                            var html = "";
                            if (data.data.length <= 0) {
                                html += `
                                <div class="col-md-12 mt-2"  >
                                    <h6>
                                        <i class="fa fa-chevron-right fa-fw"  ></i> Sin permisos seleccionados
                                    </h6>
                                </div>
                                `;
                            }else{
                                if (data.data.find( (e) => e == '*') ){
                                    html += `
                                    <div class="col-md-12 mt-2"  >
                                        <h6>
                                            <i class="fa fa-chevron-right fa-fw"  ></i> Este usuario tiene todos los permisos.
                                        </h6>
                                    </div>
                                    `;
                                }else{
                                    for(let i = 0; i < data.data.length;i++ ){
                                    html +=`
                                        <div class="col-md-12 mt-2"  >
                                            <h6>
                                            <i class="fa fa-chevron-right fa-fw"  ></i>${data.data[i].codigo}
                                            </h6>
                                            <p>
                                                ${data.data[i].descripcion}
                                            </p>
                                        </div>
                                    `
                                }
                                }

                            }
                            $('#contenedorPermisos').append(html);

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
