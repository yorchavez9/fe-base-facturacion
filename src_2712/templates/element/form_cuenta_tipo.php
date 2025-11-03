<div class="modal fade" tabindex="-1" id="formCuentaTipoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tipo de Cuenta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCuentaTipo" onsubmit="FormCuentaTipo.Guardar(event);" >
                    <p>
                        <label>Nombre</label>
                        <input type="text" class="form-control" name="nombre" autocomplete="off" />

                    </p>
                    <p>
                        <label>Descripción</label>
                        <textarea class="form-control" name="descripcion"></textarea>
                    </p>
                    <p>
                        <input type="hidden" name="id" value="0"/>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" >
    var FormCuentaTipo = {
        guardar_callback : null,
        setEventoGuardar: function (fn) {
            this.guardar_callback = fn;
        },
        Nuevo: function () {
            $('#formCuentaTipoModal').modal('show');
            FormCuentaTipo.Limpiar();
        },
        Cerrar: function () {
            $('#formCuentaTipoModal').modal('hide');
        },

        Editar: function(id){
            $('#formCuentaTipoModal').modal('show');
            FormCuentaTipo.Fetch(id);
        },
        setDatos : function(datos){
            $("#formCuentaTipo [name=nombre]").val(datos.nombre);
            $("#formCuentaTipo [name=descripcion]").val(datos.descripcion);
            $("#formCuentaTipo [name=id]").val(datos.id);
        },
        Limpiar : function(datos){
            $("#formCuentaTipo [name=nombre]").val("");
            $("#formCuentaTipo [name=descripcion]").val("");
            $("#formCuentaTipo [name=id]").val("");
        },
        Fetch: function(id){
            var endpoint = `${base}cuenta-tipos/get-one/` + id;
            $.ajax({
                url: endpoint,
                data: {},
                type:'POST',
                success : function(r){
                    console.log(r);
                    if(r.success){
                        FormCuentaTipo.setDatos(r.data);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });
        },

        Guardar: function(g) {

            g.preventDefault();
            var datos = $("#formCuentaTipo").serializeObject();

            var endpoint = `${base}cuenta-tipos/save`;
            $.ajax({
                url: endpoint,
                data: datos,
                type:'POST',
                success : function(r){
                    FormCuentaTipo.guardar_callback();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });

        }
    };


</script>
