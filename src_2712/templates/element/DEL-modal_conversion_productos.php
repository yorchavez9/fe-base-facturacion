<div class="modal fade " id="modalConversionProductos" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalConversionProductosLabel" aria-hidden="true">
    <div class="loading" id="loading" hidden></div>
    <div class="modal-dialog  modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalConversionProductosTitle"> <i class="fas fa-exchange-alt"></i> Conversion de Productos</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100">
                    <div class="form-group">
                        <label for="destino">Almacenes</label>
                        <select class="form-control form-control-sm" id="almacen">
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="destino">Producto Origen</label>
                            <input name="producto_origen" id="producto_origen" class="form-control form-control-sm" />
                            <small id="cantidad_origen_help" class="form-text text-muted">Stock <span id="stock_origen">0</span> </small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="cantidad_origen">Cantidad</label>
                            <input name="cantidad_origen" id="cantidad_origen" type="number" class="form-control form-control-sm" value="0" readonly/>
                            <div  id="invalidCheck3Feedback" class="invalid-feedback">
                                Cantidad invalida o stock insuficiente.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="destino">Producto Destino</label>
                            <input name="producto_destino" id="producto_destino" class="form-control form-control-sm" />
                            <small id="cantidad_destino_help" class="form-text text-muted">Stock <span id="stock_destino"> 0 </span> </small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="cantidad_destino">Cantidad</label>
                            <input name="cantidad_destino" id="cantidad_destino" type="number" class="form-control form-control-sm" value="0" readonly/>
                            <div  id="invalidCheck3Feedback" class="invalid-feedback">
                                Cantidad invalida.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="px-2">
                        <button class="btn btn-sm btn-primary" onclick="ModalConversionProductos.openFormItem()"><i class="fas fa-plus"></i> Producto </button>
                    </div>
                    <div class="px-2">
                        <button class="btn btn-sm btn-primary" onclick="ModalConversionProductos.guardar()" id="btn_convertir" disabled><i class="fas fa-exchange-alt"></i> Convertir </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->element('form_items') ?>
<script>
    var ModalConversionProductos = {
        item_origen: null,
        item_destino: null,
        cantidad_origen : null,
        cantidad_destino : null,
        cactual_origen : 0,
        cactual_destino : 0,
        almacen_id : 0,
        callback: null,
        init :function(){
            ModalConversionProductos.getAlmacenes()
            $("#producto_origen").autocomplete({
                serviceUrl: base + 'items/ajax-filter-items',
                onSelect: function (item) {
                    console.log(item);
                    var id_item = item.id;
                    var id_prod = (ModalConversionProductos.item_origen != null)?ModalConversionProductos.item_origen.id:0;
                    if(id_item != id_prod){
                        ModalConversionProductos.cargarProducto(item.id, 'origen');
                        ModalConversionProductos.activarCantidad();
                    }
                }
            });
            $("#producto_destino").autocomplete({
                serviceUrl: base + 'items/ajax-filter-items',
                onSelect: function (item) {
                    console.log(item)
                    var id_item = item.id;
                    var id_prod = (ModalConversionProductos.item_destino != null)?ModalConversionProductos.item_destino.id:0;
                    if(id_item != id_prod){
                        ModalConversionProductos.cargarProducto(item.id, 'destino');
                        ModalConversionProductos.activarCantidad();
                    }
                }
            });
            $("#cantidad_destino, #cantidad_origen").on('change', function(){
                if( parseInt(this.value) > 0 && Number.isInteger(parseInt(this.value))){
                    $(this).removeClass('is-invalid');
                }else{
                    $(this).addClass('is-invalid');
                    $(this).val('');
                }
                ModalConversionProductos.validar();
            })
            $("#cantidad_destino, #cantidad_origen").on('keyup', function(){
                if( parseInt(this.value) > 0 && Number.isInteger(parseInt(this.value))){
                    $(this).removeClass('is-invalid');
                }else{
                    $(this).addClass('is-invalid');
                    $(this).val('');
                }
                ModalConversionProductos.validar();
            })
            $("#almacen").on('change', function(){
                ModalConversionProductos.limpiar();
                ModalConversionProductos.almacen_id = $("#almacen").val();
                ModalConversionProductos.empresa_id = $("#empresa").val();
            })
           
            $('#modalConversionProductos').on('hidden.bs.modal', function (e) {
                ModalConversionProductos.limpiar();
                ModalConversionProductos.getAlmacenes();
            })
        },
        getAlmacenes(){
            $.ajax({
                url     :   base + "almacenes/get-all",
                type    :   'GET',
                dataType:   'JSON',
                success :   function (data, status, xhr) {
                    console.log(data);
                    if(data.success == true){
                        var str = '';
                        data.data.forEach(e => {
                            str += `<option value='${e.id}'>${e.nombre}</option>`;
                        })
                        $("#almacen").html(str);
                        if(data.data[0]){
                            ModalConversionProductos.almacen_id = data.data[0].id;
                        }
                    }else{
                        alert(data.message);
                    }
                    ModalConversionProductos.activarCantidad();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },
        cargarProducto :function(item_id, tipo){
            $.ajax({
                url     :   base + "items/ajax-get-item-stock",
                data    :   {item_id: item_id, almacen_id: ModalConversionProductos.almacen_id},
                type    :   'GET',
                dataType:   'JSON',
                success :   function (data, status, xhr) {
                    console.log(data);
                    if(data.success == true){
                        if(data.data.stock != null){

                            var can = parseInt(data.data.stock.stock);
                            if(tipo == 'origen'){
                                ModalConversionProductos.cantidad_origen = can;
                                ModalConversionProductos.item_origen = data.data;
                                ModalConversionProductos.cactual_origen = can;
                                $("#stock_origen").html(can);
                                $("#cantidad_origen").val('0');
                            }else if(tipo == 'destino'){
                                ModalConversionProductos.cantidad_destino = can;
                                ModalConversionProductos.item_destino = data.data;
                                ModalConversionProductos.cactual_destino = can;
                                $("#stock_destino").html(can);
                                $("#cantidad_destino").val('0');
                            }
                            ModalConversionProductos.activarCantidad();
                        }else{
                            alert('Producto sin registro de Stock, contactar con el Administrador');
                        }
                    }else{
                        alert(data.message);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },
        validar(){
            //btn_convertir
            var cantidad_origen = parseInt($("#cantidad_origen").val());
            var cantidad_destino = parseInt($("#cantidad_destino").val());
            var c_origen = parseInt(ModalConversionProductos.cantidad_origen);
            var estado = false;
            if( c_origen >= cantidad_origen && cantidad_destino != '' && cantidad_destino >=1){
                $("#btn_convertir").attr('disabled', false);
                estado = true;
            }else{
                $("#btn_convertir").attr('disabled', true);
                estado = false;
            }
            if(ModalConversionProductos.item_origen != null){
                if(c_origen >= cantidad_origen){
                    $("#cantidad_origen").removeClass('is-invalid');                
                    estado = true;
                }else{
                    $("#cantidad_origen").addClass('is-invalid');                
                    estado = false;
                }
            }

            if(ModalConversionProductos.item_destino != null){
                if(cantidad_destino != '' && cantidad_destino >=1){
                    $("#cantidad_destino").removeClass('is-invalid');                
                    estado = true;
                }else{
                    $("#cantidad_destino").addClass('is-invalid');                
                    estado = false;
                }
            }
            return estado
        },
        guardar(){
            $("#loading").attr('hidden',false)
            var estado = ModalConversionProductos.validar();
            if(!estado){
                return
            }
            var item_origen_id = (ModalConversionProductos.item_origen != null )? ModalConversionProductos.item_origen.id : 0 ;
            var item_destino_id = (ModalConversionProductos.item_destino != null )? ModalConversionProductos.item_destino.id : 0 ;
            if(item_origen_id === item_destino_id){
                alert("Los produtos no pueden ser iguales");
                $("#loading").attr('hidden',true)

                return
            }
            $.ajax({
                url     :   base + "conversion-historial/ajax-conversion-items",
                data    :   {
                    item_origen : item_origen_id ,
                    item_destino : item_destino_id ,
                    almacen_id : ModalConversionProductos.almacen_id,
                    cantidad_origen : $("#cantidad_origen").val() ,
                    cantidad_destino : $("#cantidad_destino").val() 
                },
                type    :   'POST',
                dataType:   'JSON',
                success :   function (data, status, xhr) {
                    console.log(data);
                    if(data.success == true){
                        alert("Se actualizaron los productos")
                        $("#modalConversionProductos").modal('hide');
                    }else{
                        alert(data.message);
                    }
                    $("#loading").attr('hidden',true)
                    if(ModalConversionProductos.callback != null){
                        ModalConversionProductos.callback();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                    $("#loading").attr('hidden',true)
                }
            });
        },
        openFormItem(){
            FormItems.Nuevo();
        },
        activarCantidad(){
            if($("#producto_origen").val() != '' && ModalConversionProductos.item_origen != null){
                $("#cantidad_origen").attr('readonly',false);
            }else{
                $("#cantidad_origen").attr('readonly',true);
            }
            if($("#producto_destino").val() != '' && ModalConversionProductos.item_destino != null){
                $("#cantidad_destino").attr('readonly',false);
            }else{
                $("#cantidad_destino").attr('readonly',true);
            }
        },
        limpiar(){
            ModalConversionProductos.almacen_id = 0;
            ModalConversionProductos.item_origen = null;
            ModalConversionProductos.item_destino = null;
            ModalConversionProductos.cantidad_origen = null;
            ModalConversionProductos.cantidad_destino = null;
            ModalConversionProductos.cactual_origen = 0;
            ModalConversionProductos.cactual_destino = 0;
            $("#producto_origen").val('');
            $("#cantidad_origen").val('0');
            $("#producto_destino").val('');
            $("#cantidad_destino").val('0');
            $("#stock_origen").html('0');
            $("#stock_destino").html('0');
            $("#cantidad_origen, #cantidad_destino").removeClass('is-invalid');
            $("#btn_convertir").attr('disabled', true);
            ModalConversionProductos.activarCantidad();
        }
    }
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