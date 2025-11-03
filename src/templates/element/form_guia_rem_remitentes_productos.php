

<div class="modal fade"
     id="formGuiaRemRemProductosModal"
     tabindex="-1" role="dialog"
     aria-labelledby="formGuiaRemRemProductosLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formGuiaRemRemProductosLabel">Agregar un Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formGuiaRemRemProductos" >

                    <div class="row">
                        <div class="col-12 col-sm-12">

                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-bong fa-fw"></i>
                                    </span>
                                </div>
                                <input type="text" name="item_nombre"  autocomplete="off" class="form-control form-control-sm" placeholder="Nombre del producto">

                            </div>

                            <input type="hidden" name="item_id">
                            <input type="hidden" name="item_codigo">
                            <input type="hidden" name="item_unidad_medida">

                        </div>


                        <div class="col-12 col-sm-8 mt-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">
                                        Unidad de Medida
                                    </label>
                                </div>
                                <select class="form-control form-control-sm" name="item_unidad_medida" id="item_unidad_medida">
                                    <option value="NIU"> NIU </option>
                                </select>
                            </div>
                        </div>

                        <div class="col mt-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        Cantidad
                                    </span>
                                </div>
                                <input type="number" min="1" step="any" name="item_cant" class="form-control form-control-sm" autocomplete="off"  placeholder="Cantidad">
                            </div>
                        </div>


                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <button onclick="FormGuiaRemRemProductos.agregarProductos(event)" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i> Guardar</button>
                        </div>
                    </div>

                    <input type="hidden" name="id">
                </form>

            </div>
        </div>
    </div>
</div>

<script>

    var FormGuiaRemRemProductos = {

        IdFormulario : 'formGuiaRemRemProductos',

        IdModal : 'formGuiaRemRemProductosModal',

        verf:true,

        num:true,

        edit: false,

        Nuevo: function () {
            $(`#${this.IdModal}`).modal('show');
            this.Limpiar();
            this.edit=false;
        },

        setCampo  : function(field, value){
            $(`#${this.IdFormulario} [name=${field}]`).val(value);
        },
        getCampo  : function(field){
            return $(`#${this.IdFormulario} [name=${field}]`).val();
        },

        Limpiar: function () {
            this.setCampo('item_id', '');
            this.setCampo('item_codigo', '');
            this.setCampo('item_nombre', '');
            this.setCampo('item_cant', 1);
            this.verf = true;
            this.num = true;
            this.verfCampos();
        },
        agregarProductos : function(e){
            e.preventDefault();
            if( $(`[name=item_nombre]`).val()=='' || $(`[name=item_cant]`).val()=='' || $(`[name=item_costo]`).val()==''  )
            {

                this.verf = false;
                this.verfCampos();
                return;
            }
            else if($(`[name=item_cant]`).val() <= 0 )
            {
                this.num = false;
                this.verfCampos();
                return;
            }
            if(this.edit == false)
            {
                GuiaremRemitenteAdd.agregarItem();
            }
            else
            {
                GuiaremRemitenteAdd.editarItem();
            }
            this.Cerrar();


        },
        verfCampos : function()
        {
            if(this.verf == false)
            {
                // document.getElementById("verf_campos").innerHTML = "";
                // document.getElementById("verf_campos").innerHTML =
                //     "<label class=' text-white rounded p-1 ml-3' style='background-color: #DC8484'>* Todos los campos son obligatorios *</label>"
                this.verf=true;
            }
            else if(this.num == false)
            {
                // document.getElementById("verf_campos").innerHTML = "";
                // document.getElementById("verf_campos").innerHTML =
                //     "<label class=' text-white rounded p-1 ml-3' style='background-color: #F1D25F'>* La cantidad debe ser distinta y mayor a 0 *</label>"
                this.num =true;
            }
            else
            {
                // document.getElementById("verf_campos").innerHTML = "";
            }

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
        Editar: function (data) {
            this.Nuevo(data.item_unidad);
            this.setDatos(data);
            this.edit=true;


        },
        setDatos: function (d) {
            this.setCampo('item_id', d.item_id);
            this.setCampo('item_codigo', d.item_codigo);
            this.setCampo('item_nombre', (d.item_nombre?d.item_nombre:d.descripcion));
            this.setCampo('item_cant', d.cantidad );
        },


    };
</script>
