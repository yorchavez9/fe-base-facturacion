<div class="modal fade" id="modalItemPrecios" tabindex="-1" role="dialog" aria-labelledby="modalMarca" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><label id="nombre_item"></label> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="max-height: 260px;height: 260px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Precio</th>
                                <th scope="col">Descripción</th>
                            </tr>
                        </thead>
                        <tbody id="precios_tabla">
                            <tr>
                                <td>the Bird</td>
                                <td>the Bird</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <form onsubmit="return ItemPrecios.nuevoPrecio(event)" id="form_n_precios">
                <div class="modal-footer">
                    <input name="item_precio" class="form-control" placeholder="Precio" type="number" step="0.01" required />
                    <input name="item_descripcion" class="form-control" placeholder="Descripción"/>
                    <button type="submit" class="btn btn-primary" id="btn_precio" >Agergar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    #modalItemPrecios #precios_tabla tr:hover{
        cursor: pointer;
        background-color: #c5faf5;
    }
</style>
<script>
    var ItemPrecios = {
        titulo: '',
        item_id: 0,
        meCallback: null,
        abrirPrecios : function (item_id, item_nombre, f_callback = null) {
            ItemPrecios.limpiarTabla()
            ItemPrecios.item_id = item_id;
            ItemPrecios.titulo = `Precios para ${item_nombre}`;
            ItemPrecios.meCallback = f_callback;
            $("#nombre_item").html(ItemPrecios.titulo)
            $('#modalItemPrecios').modal('show');
            $("#precios_tabla").html('')
            ItemPrecios.getPrecios(item_id);
        },
        getPrecios : function async (item_id){
            $.ajax({
                url     :   base + "item-precios/precios-producto/" + item_id,
                data    : { },
                type    :   'GET',
                // dataType:   'JSON',
                success :   function (response) {
                    var str = '';
                    console.log(response)
                    if(response.success){                        
                        response.data.forEach( e => {
                            str +=  `<tr onclick="ItemPrecios.enviarPrecio(${e.precio})">
                                <td>${ Number.parseFloat(e.precio).toFixed(2) }</td>
                                <td>${ e.descripcion }</td>
                            </tr>`;
                        });
                    }
                    if(str === ''){
                        str = `<tr><td colspan="100%"> Sin registros </td></tr>`;
                    }
                    $("#precios_tabla").html(str)
                },error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                }
            });
        },
        nuevoPrecio : function async (event){
            event.preventDefault();
            
            $("#btn_precio").attr('disabled' , true);
            if(Number.parseFloat($("[name=item_precio]").val() ) <= 0){
                $("#btn_precio").attr('disabled' , false);
                return alert("Debe ingresar un precio mayor a cero.")
            }
            var fdata = new FormData(document.getElementById('form_n_precios'));
            $.ajax({
                url     :   base + "item-precios/nuevo-precio/" + ItemPrecios.item_id,
                data    : fdata,
                type    : 'POST',
                processData: false,
                contentType: false,
                success :   function (response) {
                    if(response.success){                        
                        alert("Precio agregado.")
                        ItemPrecios.limpiar()
                        ItemPrecios.getPrecios(ItemPrecios.item_id);
                    }
                },error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                }, complete: function (){
                    $("#btn_precio").attr('disabled' , false);
                }
            });
            return false;
        },
        limpiar : function (){
            $("[name=item_precio]").val('');
            $("[name=item_descripcion]").val('');
        },
        limpiarTabla : function (){
            $("#nombre_item").html('Precios');
            $("#precios_tabla").html('<tr><td colspan="100%"> Sin registros </td></tr>');
        },
        enviarPrecio : function ( precio ){
            if(ItemPrecios.meCallback != null){
                ItemPrecios.meCallback(precio)
                $('#modalItemPrecios').modal('hide');
            }
        }
    }
</script>