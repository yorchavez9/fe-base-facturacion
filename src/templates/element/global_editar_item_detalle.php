
<div class="modal fade" id="modalEditarItemDetalle" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="item_nombre"></h6>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="row" id="div_serv_desc">
                            <div class="col-md-12 mb-2">
                                <textarea name="servicio_desc" class="form-control" id="servicio_desc" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-comment fa-fw"></i>
                                </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Comentario" name="item_comentario" id="item_comentario">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-5 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-weight fa-fw"></i> &nbsp; Cantidad
                                                    </span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Cantidad" name="item_cantidad" id="item_cantidad">
                                </div>
                            </div>
                            <div class="col-md-7 mb-2">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-coins fa-fw"></i> &nbsp; Precio
                                                    </span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Precio"  name="item_precio" id="item_precio">
                                    <div style="padding-left:5px; display:flex; align-items:center" onclick="FormEditarProductoDetalle.abrirModalPrecios()">
                                        <i class="fa fa-eye"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2" id="div_afect_igv">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Afectación IGV</span>
                            </div>
                            <select class="form-control"  name="afectacion_igv">
                                <option value="10">Gravado - Operación Onerosa</option>
                                <option value="20">Exonerado - Operación Onerosa</option>
                                <option value="30">Inafecto - Operación Onerosa</option>
                            </select>
                        </div>
                    </div>



                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="FormEditarProductoDetalle.guardar()"> <i class=" fa fa-save fa-fw"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>




<script>
    var FormEditarProductoDetalle =
        {
            IdModal :   "modalEditarItemDetalle",
            item_id :  0 ,
            es_servicio :0,
            item_nombre : '',
            abrir   :   function(item_id,nombre,comentario,cantidad,precio,afectacion_igv='GRAVADO')
            {
                $(`#${this.IdModal}`).modal("show");
                this.item_id = item_id;
                this.prod_nombre = nombre;
                $(`#${this.IdModal} #item_nombre`).html( `<i class="fa fa-box fa-fw"></i>&nbsp;&nbsp; ${nombre}`);
                $(`#${this.IdModal} [name=item_comentario]`).val(comentario);
                $(`#${this.IdModal} [name=item_cantidad]`).val(cantidad);
                $(`#${this.IdModal} [name=item_precio]`).val(precio);

                if(typeof afectacion_igv != 'undefined' && afectacion_igv !== "")
                {
                    $(`#${FormEditarProductoDetalle.IdModal} select[name=afectacion_igv]`).val(afectacion_igv).change();
                }

                if(this.es_servicio == 1)
                {
                    $("#div_serv_desc").show();
                    $("#servicio_desc").val(nombre);
                }else{
                    $("#div_serv_desc").hide();
                    $("#servicio_desc").val("");
                }

            },
            callback : function(){},
            guardar     :   function()
            {

                var comentario = $(`#${this.IdModal} [name=item_comentario]`).val(),
                    cantidad = $(`#${this.IdModal} [name=item_cantidad]`).val(),
                    precio = $(`#${this.IdModal} [name=item_precio]`).val(),
                    afectacion_igv = $(`#${this.IdModal} [name=afectacion_igv]`).val(),
                    nombre = $(`#${this.IdModal} [name=servicio_desc]`).val();

                var item_data = {
                    id          :   parseInt(this.item_id) ,
                    comentario  :   comentario,
                    cantidad    :   cantidad,
                    precio      :   precio,
                    afectacion_igv  :   afectacion_igv,
                    nombre      :   nombre
                }

                FormEditarProductoDetalle.es_servicio = 0;

                FormEditarProductoDetalle.callback(item_data);
                $(`#${this.IdModal}`).modal("hide");


            },
            abrirModalPrecios     :   function() {
                ItemPrecios.abrirPrecios( FormEditarProductoDetalle.item_id , FormEditarProductoDetalle.item_nombre, FormEditarProductoDetalle.cambiarPrecioTabla )
            },
            cambiarPrecioTabla     :   function(precio) {
                $("#item_precio").val(precio)
            }
        }
</script>
