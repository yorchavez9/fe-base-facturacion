<div class="modal fade" tabindex="-1" id="formCuentaCanalLlegadaModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Canal de Llegada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCuentaCanalLlegada" onsubmit="FormCuentaCanalLlegada.Guardar(event);" >
                    <p>
                        <label>Canal de Llegada</label>
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
    var FormCuentaCanalLlegada = {
        guardar_callback : null,
        setEventoGuardar: function (fn) {
            this.guardar_callback = fn;
        },
        Nuevo: function () {
            $('#formCuentaCanalLlegadaModal').modal('show');
            FormCuentaCanalLlegada.Limpiar();
        },
        Cerrar: function () {
            $('#formCuentaCanalLlegadaModal').modal('hide');
        },

        Editar: function(id){
            $('#formCuentaCanalLlegadaModal').modal('show');
            FormCuentaCanalLlegada.Fetch(id);
        },
        setDatos : function(datos){
            $("#formCuentaCanalLlegada [name=nombre]").val(datos.nombre);
            $("#formCuentaCanalLlegada [name=descripcion]").val(datos.descripcion);
            $("#formCuentaCanalLlegada [name=id]").val(datos.id);
        },
        Limpiar : function(datos){
            $("#formCuentaCanalLlegada [name=nombre]").val("");
            $("#formCuentaCanalLlegada [name=descripcion]").val("");
            $("#formCuentaCanalLlegada [name=id]").val("");
        },
        Fetch: function(id){
            var endpoint = `${base}cuenta-canal-llegadas/get-one/` + id;
            $.ajax({
                url: endpoint,
                data: {},
                type:'POST',
                success : function(r){
                    console.log(r);
                    if(r.success){
                        FormCuentaCanalLlegada.setDatos(r.data);
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
            var datos = $("#formCuentaCanalLlegada").serializeObject();
            var endpoint = `${base}cuenta-canal-llegadas/save`;
            $.ajax({
                url: endpoint,
                data: datos,
                type:'POST',
                success : function(r){

                    console.log(r);
                    FormCuentaCanalLlegada.guardar_callback();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });

        }
    };


</script>
