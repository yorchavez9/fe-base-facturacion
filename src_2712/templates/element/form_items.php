<div class="modal fade"
     id="formItemsModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formItemsLabel"
     aria-hidden="true">
    <div class="loading" id="loading" hidden></div>

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formItemsLabel">Nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formItems" onsubmit="FormItems.Guardar(event)">

                    <div class="form-row my-3">
                        <div class="col-md-6">
                            <div class="input-group" name="divcategoria">
                                <select name="categoria_id" class="form-control form-control-sm"></select>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm" type="button" id="btnAgregarCategoria">
                                        <i class="fas fa-plus fa-fw"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group" name="divmarca">
                                <select name="marca_id" class="form-control form-control-sm"></select>
                                <span class="input-group-btn">
                            <button class="btn btn-sm btn-primary" type="button" id="btnAgregarMarca">
                                <span class="glyphicon glyphicon-search" aria-hidden="true">
                                </span>
                                <i class="fas fa-plus"></i>
                                </button>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-coins fa-fw"></i>
                                    </span>
                                </div>
                                <select name="item_tipo_moneda" class="form-control form-control-sm">
                                    <option value="PEN">PEN</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-code fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="codigo" class="form-control" autocomplete="off" placeholder="Código">
                                <div class="input-group-append">
                                    <a class="btn btn-primary text-white" href="javascript:FormItems.GenerarCodigo()"><i class="fa fa-fw fa-sync-alt"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="nombre" class="form-control" autocomplete="off" placeholder="Nombre del producto" required>
                            </div>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-sm-12">
                            <textarea name="descripcion" class="form-control" placeholder="Descripcion del producto"></textarea>
                        </div>
                    </div>

                    <div class="row my-3">

                        <div class="col-sm-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        P. Venta
                                    </span>
                                </div>
                                <input type="number" step="any" name="precio_venta" class="form-control" autocomplete="off" placeholder="P. Venta" >
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        P. Compra
                                    </span>
                                </div>
                                <input type="number" step="any" name="precio_compra" class="form-control" autocomplete="off" placeholder="P. Compra"  >
                            </div>

                        </div>

                        <div class="col-sm-4">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        ¿Incluye IGV?
                                    </span>
                                </div>
                                <select name="inc_igv" class="form-control">
                                    <option value="1">Si Incluye</option>
                                    <option value="0">No Incluye</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-upload fa-fw"></i>
                                    </span>
                                </div>
                                <input type="file" name="file_img_ruta" accept="image/*" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i> Guardar</button>
                        </div>
                    </div>
                    <input type="hidden" name="id">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Element("global_modal_marca");
echo $this->Element("global_modal_categoria");
?>
<script>
    var FormItems = {

        IdFormulario: 'formItems',

        IdModal: 'formItemsModal',

        Nuevo: function () {
            this.Limpiar();
            $(`#${this.IdModal}`).modal('show');
            this.poblarMarcas(0);
            this.poblarCategorias(0);
            $("#modalCategoriaForm").submit(function(e){
                e.preventDefault()
                FormItems.agregarCategoria();
            });
            $("#modalMarcaForm").submit(function(e){
                e.preventDefault();
                FormItems.agregarMarca()
            });
            $("#btnAgregarCategoria").click(function(){
                $('#modalCategoria').modal("show");
                $('#loading').attr("hidden",false);
            });
            $("#btnAgregarMarca").click(function(){
                $('#modalMarca').modal("show");
                $('#loading').attr("hidden",false);
            });
            $('#modalMarca').on('hidden.bs.modal', function (event) {

                $('#loading').attr("hidden",true);

            })

            $('#modalCategoria').on('hidden.bs.modal', function (event) {

                $('#loading').attr("hidden",true);

            })
        },

        Limpiar: function () {
            this.setCampo('id', '');
            this.setCampo('codigo', '');
            this.setCampo('nombre', '');
            this.setCampo('descripcion', '');
            this.setCampo('precio_compra', '0.00');
            this.setCampo('precio_venta', '0.00');
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

        setDatos: function (d) {
            this.setCampo('id', d.id);
        },

        Cerrar : function () {
            $(`#${this.IdModal}`).modal('hide');
            this.Limpiar();
        },

        Guardar: function (e) {
            e.preventDefault();
            var endpoint = `${base}items/save`;
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
                    if (r.success) {
                        FormItems.Cerrar();
                    } else {
                        //alert(r.message);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },

        Editar: function (id) {
            this.Limpiar();
            $(`#${this.IdModal}`).modal('show');
            this.fetchDatos(id);
        },

        GenerarCodigo: function () {
            var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
            var codigo = "";
            for (var i = 0; i < 8; i++) {
                codigo += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.setCampo('codigo', codigo);
        },

        fetchDatos: function(id) {
            var endpoint = `${base}usuarios/get-one/${id}`;
            $.ajax({
                url: endpoint,
                data: {},
                type:'GET',
                success : function(r){
                    if(r.success){
                        FormItems.setDatos(r.data);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }

            });
        },

        poblarMarcas: function (id) {
            var endpoint = `${base}items/get-marcas`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: function (r) {
                    if (r.success) {
                        var options = `<option value="0">- Seleccione Marca -</option>`;
                        $(r.data).each(function (i, o) {
                            if (o.id == id) {
                                options += `<option value="${o.id}" selected>${o.nombre}</option>`;
                            } else {
                                options += `<option value="${o.id}">${o.nombre}</option>`;
                            }
                        });

                        $('[name=marca_id]').html(options);
                    }
                }
            });
        },

        poblarCategorias: function (id) {
            var endpoint = `${base}items/get-categorias`;
            $.ajax({
                url: endpoint,
                type: 'GET',
                data: '',
                success: function (r) {
                    if (r.success) {
                        var options = `<option value="0">- Seleccione Categoria -</option>`;
                        $(r.data).each(function (i, o) {
                            if (o.id == id) {
                                options += `<option value="${o.id}" selected>${o.nombre}</option>`;
                            } else {
                                options += `<option value="${o.id}">${o.nombre}</option>`;
                            }
                        });

                        $('[name=categoria_id]').html(options);
                    }
                }
            });
        },


        agregarCategoria :function() {
            var nombre  =   $("#modalCategoriaForm [name=nombre]").val();
            var descripcion =   $("#modalCategoriaForm [name=descripcion]").val();
            var data = $("#modalCategoriaForm").serializeObject();

            $.ajax({
                url     :   base + "item-categorias/guardar-categoria-ajax",
                //data    : {id:'', nombre: nombre, descripcion: descripcion },
                data: data,

                type    :   'POST',
            //   dataType:   'JSON',
                success :   function (response) {
                    $('#modalCategoria').modal("hide");
                    $('#loading').attr("hidden",true);
                    FormItems.poblarCategorias(response.data.id);
                },error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr);
                    console.log(thrownError);
                }
            });
            return false;
        },
        agregarMarca :function() {
            var nombre = $("#modalMarcaForm [name=nombre]").val();
            var data = $("#modalMarcaForm").serializeObject();
            $.ajax({
                url     :   base + "item-marcas/guardar-json-ajax",
                //data    : { id:'', nombre: nombre},
                type    :   'POST',
                data: data,
                // dataType:   'JSON',
                success :   function (response) {
                    $('#modalMarca').modal("hide");
                    $('#loading').attr("hidden",true);
                    FormItems.poblarMarcas(response.data.id);
                },error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                }
            });
            return false;
        }
    };
</script>
<style>
    /* Absolute Center Spinner */
    .loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    }

    /* Transparent Overlay */
    .loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
        background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

    background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
    }

    /* :not(:required) hides these rules from IE9 and below */
    .loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
    }

    .loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 1200ms infinite linear;
    -moz-animation: spinner 1200ms infinite linear;
    -ms-animation: spinner 1200ms infinite linear;
    -o-animation: spinner 1200ms infinite linear;
    animation: spinner 1200ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
    box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
    }

    /* Animation */

    @-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    }
    @-moz-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    }
    @-o-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    }
    @keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
    }
</style>




















