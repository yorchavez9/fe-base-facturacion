<div class="modal fade"
     id="formEstablecimientoUsuarioModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formEstablecimientoUsuarioLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formEstablecimientoUsuarioLabel">Lista de establecimientos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <h5> Usuario: <label id="label_usuario"></label> </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Ciudad</th>
                                <th>Dirección</th>
                                <th class="actions"> Acciones </th>
                            </tr>
                        </thead>
                        <tbody id="lista_establecimientos">
                        </tbody>
                    </table>
                </div>
                <form id="formEstablecimientoUsuario" method="POST" onsubmit="FormEstablecimientoUsuario.Guardar(event)">
                    <input type="hidden" id="usuario_text_id" name="usuario_id">
                    <button type="submit" class="btn btn-sm btn-outline-primary" id="btn_save">
                        <i class="fas fa-save fa-fw"></i>
                        Guardar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var FormEstablecimientoUsuario = {
        IdFormulario: 'formEstablecimientoUsuario',
        IdModal: 'formEstablecimientoUsuarioModal',

        Init: function (usuario_id = 0 , usuario_label = '') {
            $(`#${this.IdModal}`).modal('show');
            this.Limpiar();
            $("#label_usuario").html(usuario_label);
            $(`#${this.IdFormulario} #usuario_text_id`).val(usuario_id);
            this.poblarAlmacenes(usuario_id);
        },

        Limpiar: function () {
            $("#label_usuario").html();
            $("#lista_establecimientos").html();
            $(`#${this.IdFormulario} #usuario_text_id`).val('');

        },

        Cerrar : function () {
            $(`#${this.IdModal}`).modal('hide');
        },

        poblarAlmacenes: function (id) {
            var endpoint = `${base}establecimientos/get-establecimientos-by-usuario/${id}`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: function (r) {
                    console.log(r)
                    var html = ``;
                    $(r.data).each(function (i, alma) {
                        html += `<tr>
                            <td>
                                ${alma.nombre}<br/>
                                <small>${alma.codigo}</small>
                            </td>
                            <td>
                                ${alma.departamento} <br/>
                                ${alma.provincia}
                            </td>
                            <td>
                                ${alma.direccion} <br>
                                ${alma.distrito}
                            </td>
                            <td class="actions">
                                <label>
                                    <input type="checkbox" name="almacenes[${alma.id}]" form="formEstablecimientoUsuario" ${(alma.seleccionado) ? 'checked':'' } />
                                    Permitido
                                </label>
                            </td>
                        </tr>`
                    });
                    $("#lista_establecimientos").html(html);
                }
            });
        },
        getDatos: function () {
            var formElement = document.getElementById(this.IdFormulario);
            var formData = new FormData(formElement);

            return formData;
        },
        Guardar: function (e) {
            e.preventDefault();
            var endpoint = `${base}establecimientos/save-usuario-establecimiento`;
            var datos = this.getDatos();
            $(`#${this.IdFormulario} #btn_save`).attr('disabled', true);
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
                        FormEstablecimientoUsuario.Cerrar();
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                },
                complete: function () {
                    $(`#${this.IdFormulario} #btn_save`).attr('disabled', false);
                }
            });
        },

    };
</script>


