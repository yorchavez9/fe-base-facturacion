<div class="modal fade"
     id="modalGlobalItemsEtiquetas"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"  id="etiquetasLabel" >Etiquetas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formItemsEtiquetas" onsubmit="GlobalItemsEtiquetas.Guardar(event)" >
                    <div class="row">
                        <div class="col-md-12 my-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-crown fa-fw" ></i>&nbsp; Tipo
                                    </span>
                                </div>

                                <select name="tipo" id="etiqueta_tipo" class="form-control form-control-sm"  >
                                    <option value="" disabled >-Seleccione tipo-</option>
                                    <option value="MARCA">Marca</option>
                                    <option value="CATEGORIA">Categoria</option>
                                    <option value="ETIQUETA">Etiqueta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-book fa-fw" ></i>&nbsp; Nombre
                                    </span>
                                </div>

                                <input type="text" id="etiqueta_nombre" name="nombre" class="form-control form-control-sm" placeholder="nombre" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea
                                    name="descripcion" id="etiqueta_descripcion"
                                    placeholder="Descripcion"
                                    class="form-control form-control-sm"
                                    cols="30" rows="5" maxlength="255" ></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-sm btn-primary float-right" >
                                <i class="fa fa-save fa-fw" ></i> Guardar
                            </button>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<script>
    var GlobalItemsEtiquetas =  {
        IdModal : "modalGlobalItemsEtiquetas",
        IdFormulario : 'formItemsEtiquetas',
        IdModalTitle :    "etiquetasLabel",
        EtiquetaId : 0,
        IdLabel :   "etiquetasLabel",
        Nuevo   :   function(){
            GlobalItemsEtiquetas.limpiar();
            GlobalItemsEtiquetas.setTitle('Agregar nueva Etiqueta');
            GlobalItemsEtiquetas.Abrir();
        },
        Editar  :   function(etiqueta_id){
            GlobalItemsEtiquetas.limpiar();
            GlobalItemsEtiquetas.EtiquetaId = etiqueta_id;
            GlobalItemsEtiquetas.fetchEtiqueta(GlobalItemsEtiquetas.Abrir);

        },
        fetchEtiqueta   :   function(fn = null){
            var endpoint = `${base}etiquetas/get-one/${GlobalItemsEtiquetas.EtiquetaId}`;

            GlobalSpinner.mostrar();
            $.ajax({
                url :   endpoint,
                data    :   '',
                type    :   'GET',
                success :   function(data){
                    if(data.success){
                        GlobalItemsEtiquetas.setCampo('nombre',data.data.nombre);
                        GlobalItemsEtiquetas.setCampo('descripcion',data.data.descripcion);
                        $("select[name=tipo]").val(data.data.tipo).change() ;

                        GlobalItemsEtiquetas.setTitle(`Editar etiqueta <b>${data.data.nombre}</b>`);
                        if(fn != null){
                            fn();
                        }

                    }else{
                        alert(data.message);
                    }
                    GlobalSpinner.ocultar();
                },
                error:  function(xhr,ajaxOptions,thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                    GlobalSpinner.ocultar();
                }
            })
        },
        limpiar :   function(){
            GlobalItemsEtiquetas.EtiquetaId = 0;
            GlobalItemsEtiquetas.setCampo('nombre','');
            GlobalItemsEtiquetas.setCampo('descripcion','');
        },
        Guardar :   function(e){
            e.preventDefault();
            var endpoint = `${base}etiquetas/save/${GlobalItemsEtiquetas.EtiquetaId}`,
                data = GlobalItemsEtiquetas.getDatos();
            GlobalItemsEtiquetas.Ocultar();
            GlobalSpinner.mostrar();
            $.ajax({
                url     :   endpoint,
                data    :   data,
                type    :   'POST',
                processData: false,
                cache: false,
                contentType: false,
                success :   function(data){
                    if(data.success){
                        alert('Registro exitoso');
                        window.location.reload();

                    }else{
                        alert(data.message);
                    }

                    GlobalSpinner.ocultar();
                },
                error   :   function(xhr,ajaxOptions,thrownError){
                    alert( "Error " + xhr.status + ' : ' + thrownError + "\n\n" + 'Ha habido un error, contactar a soporte' );
                    GlobalSpinner.ocultar();
                }
            })
        },
        setCampo  : function(field, value){
            $(`#${this.IdFormulario} [name=${field}]`).val(value);
        },

        getCampo  : function(field){
            return $(`#${this.IdFormulario} [name=${field}]`).val();
        },

        getDatos: function () {
            var formElement = document.getElementById(this.IdFormulario);
            var dataForm = new FormData(formElement);

            return dataForm;
        },
        setTitle    :   function(titulo){

            $(`#${GlobalItemsEtiquetas.IdModal} #${GlobalItemsEtiquetas.IdLabel}`).html(titulo);

        },
        Abrir   :   function(){
            $(`#${GlobalItemsEtiquetas.IdModal}`).modal('show');
        },
        Ocultar :   function(){
            $(`#${GlobalItemsEtiquetas.IdModal}`).modal('hide');
        }


    }


</script>
