
$(document).ready(function(){
    // paramos la ejecucion de la tecla ENTER
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    // enter en la tecla de lectura de codigo de barras
    $(".ingresocodigo").on('keypress', function(e) {
        var codigo = $(this).val();
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            ParteSalida.buscarProductoPorCodigo(codigo);
        }
    });

    // enter en la tecla de lectura de codigo de barras
    $(".ingresoproducto").on('keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            ParteSalida.abrirBuscadorProductos()

        }
    });

    ParteSalida._table = $("#tablafiltro").DataTable({
        'select'    : true,
        'searching' : false,
        'paging' : true,
        'pageLength':10,
        'lengthChange': false,
        "language": {
            "url": base_root + "/assets/data-tables/es.json"
        },
        //"ajax": base + 'cotizaciones/get-items/?q='
    });

    $('#tablafiltro tbody').on( 'click', 'tr', function () {
        // console.log( ParteSalida._table.row( this ).data() );
        var rowdata =  ParteSalida._table.row( this ).data();
        ParteSalida.buscarProductoPorCodigo(rowdata[2]);
    } );


    // bind para limpiar datos
    $(".limpiar_formulario").click(function(){
        ParteSalida.clienteLimpiarDatos();
    });

    // binding
    $("#campoBusqueda2").on("keyup change", function(){
        var value = $("#campoBusqueda2").val();
        $("#campoBusqueda1").val(value);
    });

    newItemsList.callback = function(itemObj){
        ParteSalida.itemsAgregar(itemObj);
        ParteSalida.actualizarVentaDetalle();
    }

});



var ParteSalida =
    {
        _items : [],
        _cuenta : {},
        _persona : {},
        _totales : {},
        _table : null,
        init    :   function(data)
        {
        },
        guardar :   function(e)
        {
            e.preventDefault();
            console.log(ParteSalida._items)
        },
        /**
         * Bloquea el boton de guardado de la venta
         */
        bloquearBoton   :  function() {
            //$('#botones_venta').hide();
            $('.btn-guardar').attr('disabled', true);
        },

        /**
         * Desbloquea el boton de guardado de la venta
         */
        desbloquearBoton    :   function() {
            //$('#botones_venta').show();
            $('.btn-guardar').attr('disabled', false);
        },
        /**
         * Aqui Comienzan todas las validaciones
         * Correspondientes a la creacion de una nueva venta
         */

        /**
         * Valida que el monto de la venta no sea menor o igual a cero
         */
        validarMontoTotalVenta  :   function(total)
        {
            if(total <= 0)
            {
                alert('Debe ingresar correctamente los datos de la venta');
                return false;
            }
            return true;
        },
        /**
         * Buscar producto por Nombre en el buscador de productos
         * @param nombre
         */
        buscarProductoPorNombre     :   function(nombre){
            // var busqueda = $("input[name=nombre_producto]").val();
            var url = base + 'api/get-items/?q=' + nombre;

            ParteSalida._table.ajax.url( url ).load();
            //_table.ajax.reload();
            ParteSalida._table.clear().draw();
        },

        /**
         * Buscar producto por codigo, al momento
         * de utilizar un lector de codigo de barras
         * @param codigo
         */
        buscarProductoPorCodigo    :   function(codigo)
        {
            $.ajax({
                url     :   base + "api/get-item-by-code",
                data    :   {'codigo' : codigo},
                type    :   'GET',
                dataType:   'JSON',
                success :   function (data, status, xhr) {
                    if (data.estado === 'ok'){
                        ParteSalida.itemsAgregar(data.data);
                        ParteSalida.actualizarVentaDetalle();
                    }
                    // exista o no el producto, se limpia el campo
                    $(".ingresocodigo").val("");
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        },
        /**
         * Redondea decimales
         * @param num
         * @param dec
         * @returns {number}
         */
        roundFloat  :   function(num,dec){
            var d = 1;
            for (var i=0; i<dec; i++){
                d += "0";
            }
            return Math.round(num * d) / d;
        },
        actualizarVentaDetalle  :   function()
        {
            ParteSalida.itemsGenerarTabla();
        },

        /**
         * Agrega un item al array de items
         * @param data
         */

        itemsAgregar    :   function(data){
            var existe = false;
            $(ParteSalida._items).each(function(i,o){
                if(o.id == data.id){
                    o.cantidad += 1;
                    existe = true;
                }
            });

            if(!existe){
                data.index = ParteSalida._items.length +1;
                data.cantidad = 1;
                data.comentario = "";
                data.precio_original = data.precio_venta;
                /**
                 * Esta condicion es para verificar el tipo de moneda del
                 * producto que se está ingresando
                 */
                var venta_tipo_moneda = $('select[name=tipo_moneda]').val();
                data.actualizar = data.tipo_moneda === venta_tipo_moneda ? 1 : 0;
                /**
                 * ----------------------------------------------------
                 */

                data.valor_venta = 0;
                data.igv = 0;
                ParteSalida._items.push(data);
            }
        },


        /**
         * Este metodo genera la tabla de detalles
         * de los items
         */
        itemsGenerarTabla   :   function()
        {

            var html = "";
            $(ParteSalida._items).each(function(i, o){

                html += "<tr>";
                html += "<td>" + o.index + "</td>";
                html += "<td>" + o.codigo + "</td>";
                html += "<td>";
                if(o.img_ruta != null && o.img_ruta !== ''){
                    html += `<img src="${base_root}${o.img_ruta}" class="img-fluid" width="64px" height="64px" style="border-radius: 10px" >`;
                }else{
                    html += `<img src="${base_root}media/iconos/placeholder_items.png"  class="img-fluid" width="64px" height="64px" style="border-radius: 10px" >`;
                }
                html += "</td>";
                html += `<td>${o.nombre}<br/>`;
                html += "</td>";
                html += "</td>";
                html += "<td>" + o.unidad + "</td>";
                html += "<td class='text-center'><input type='number' class='item_data item_cantidad_"+o.id+"' value='"+o.cantidad+"'  data-id='"+o.id+"' data-index='"+i+"'  /></td>";

                html += "<td>";
                html += " <button type='button' class='btn_item_eliminar btn btn-sm' data-id='"+o.id+"' data-index='"+i+"' > <i class='fas fa-trash item_eliminar' ></i> </button>";
                html += "</td>";
                html += "</tr>";
            });

            $("tbody#detalle").html(html);
            $(".item_data").change(function(){
                var index = $(this).attr('data-index');
                ParteSalida._items[index].cantidad = Number($(this).val());
                ParteSalida.itemsGenerarTabla();
            });
            $(".btn_item_eliminar").click(function(){
                var index = $(this).attr('data-index');
                ParteSalida._items.splice(index,1);
                ParteSalida.itemsGenerarTabla()
            });


            // guardamos en el campo oculto el detalle
            var str_data = JSON.stringify(ParteSalida._items);
            //alert(str_data);
            $(".detalle_items").val(str_data);
            $(".ingresocodigo").val('');
            if(ParteSalida._items.length >=1){
                $("#btn_submit").attr('disabled', false)
                ParteSalida.desbloquearBoton();
            }else{
                $("#btn_submit").attr('disabled', true)
                ParteSalida.bloquearBoton();
            }
        },

        itemEliminarRegistro    :   function(id){
            var _items2 = [];
            for( var i = 0; i <  ParteSalida._items.length; i++){
                if (  ParteSalida._items[i].id != id) {
                    _items2.push(ParteSalida._items[i]);
                }
            }
            ParteSalida._items = _items2;

            ParteSalida.actualizarVentaDetalle();
        },
        /**
         * Actualiza los campos que pueden cambiar
         * en el detalle, como precio, cantidad y el comentario de cada producto
         */
        itemActualizarRegistro  :   function(data){

            $(ParteSalida._items).each(function(i,o){
                if(o.id === data.id){
                    o.precio_venta = data.precio;
                    o.cantidad = data.cantidad;
                    o.comentario = data.comentario;
                    o.actualizar = 1;
                }

            });
            ParteSalida.actualizarVentaDetalle();

        },

    /**
     * Abre el DataTable de busqueda de productos
     */
    abrirBuscadorProductos  :   function(){
        var producto = $("[name=nombre_producto]").val();
        newItemsList.abrir(producto);
        // $("#modalBuscadorProductos").modal("show");
        // $("#campoBusqueda2").val(producto)
        // ParteSalida.buscarProductoPorNombre(producto);
    },



};
