<div class="modal fade" tabindex="-1" role="dialog" id="formDirectorioCategoriaModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Categoría de Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="formDirectorioCategoria" onsubmit="FormDirectorioCategoria.Guardar(event)">
                    <div class="row">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-folder-open fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="nombre" class="form-control form-control-sm" autocomplete="off" placeholder="Nombre de la Categoría" />
                                <input type="hidden" name="id" value="" />
                                <div class="input-group-append">
                                    <button type="submit" id="" class="btn btn-sm btn-primary">
                                        <i class="fa fa-save fa-fw"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var FormDirectorioCategoria = {
        ID: '#formDirectorioCategoria',
        endpoint_base : `${base}directorio-categorias`,
        guardar_callback : null,
        setEventoGuardar: function (fn) {
            this.guardar_callback = fn;
        },
        Nuevo: function () {
            $(FormDirectorioCategoria.ID + 'Modal').modal('show');
            FormDirectorioCategoria.Limpiar();
        },
        Cerrar: function () {
            $(FormDirectorioCategoria.ID + 'Modal').modal('hide');
        },

        Editar: function(id){
            $(FormDirectorioCategoria.ID + 'Modal').modal('show');
            FormDirectorioCategoria.Fetch(id);
        },
        getDatos : function(){
            return $(FormDirectorioCategoria.ID).serializeObject();
        },
        setDatos : function(datos){
            $(FormDirectorioCategoria.ID + " [name=nombre]").val(datos.nombre);
            $(FormDirectorioCategoria.ID + " [name=descripcion]").val(datos.descripcion);
            $(FormDirectorioCategoria.ID + " [name=id]").val(datos.id);
        },
        Limpiar : function(datos){
            $(FormDirectorioCategoria.ID + " [name=nombre]").val("");
            $(FormDirectorioCategoria.ID + " [name=descripcion]").val("");
            $(FormDirectorioCategoria.ID + " [name=id]").val("");
        },
        Fetch: function(id){
            var endpoint = FormDirectorioCategoria.endpoint_base + `/get-one/` + id;
            $.ajax({
                url: endpoint,
                data: {},
                type:'POST',
                success : function(r){
                    console.log(r);
                    if(r.success){
                        FormDirectorioCategoria.setDatos(r.data);
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
            var datos = FormDirectorioCategoria.getDatos();
            var endpoint = FormDirectorioCategoria.endpoint_base + `/save/`;
            $.ajax({
                url: endpoint,
                data: datos,
                type:'POST',
                success : function(r){
                    console.log(r)
                    FormDirectorioCategoria.guardar_callback();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });

        }
    };
</script>
