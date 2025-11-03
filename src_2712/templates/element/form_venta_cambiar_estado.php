<div class="modal fade"
     id="formVentaCambiarEstadoModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formVentaCambiarEstadoLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formVentaCambiarEstadoLabel">Cambiar de Estado de la Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formVentaCambiarEstado">
                    <div class="row">
                        <div class="col">
                            <label>Ingrese el nuevo estado:</label>
                            <input type="text" name="estado" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <button type="submit"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>

                    <input type="hidden" name="venta_id">
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    var FormVentaCambiarEstado = {
        IdFormulario: 'formVentaCambiarEstado',
        IdModal: 'formVentaCambiarEstadoModal',
        Nuevo: function (venta_id) {

        },

        setCampo  : function(field, value){
            $(`#${this.IdFormulario} [name=${field}]`).val(value);
        },
        getCampo  : function(field){
            return $(`#${this.IdFormulario} [name=${field}]`).val();
        },
        getDatos: function () {
            var formElement = document.getElementById(this.IdFormulario);
            var formData = new FormData(formElement);

            return formData;
        },
        Cerrar : function () {
            $(`#${this.IdModal}`).modal('hide');
            this.Limpiar();
        },
        Guardar: function (e) {
            e.preventDefault();
            var endpoint = `${base}usuarios/save`;
            var datos = this.getDatos();
            $.ajax({
                url: endpoint,
                data: datos,
                type: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                success: function (r) {
                    console.log(r);
                    if (!r.success) {
                        alert(r.message);
                    } else {
                        FormUsuarios.Cerrar();
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },
    };
</script>





