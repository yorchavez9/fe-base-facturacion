$(document).ready(function(){
    $("#modalCategoriaForm").submit(function(e){
        e.preventDefault()
        agregarCategoria();
    });

    $(`#form_servicio`).submit(function (e) {
        var precio_venta = parseFloat($(`[name=precio_venta]`).val());
        var precio_items = parseFloat($(`[name=precio_items]`).val());

        if (precio_venta < precio_items) {
            e.preventDefault();
            alert('El precio del servicio no puede ser menor al costo total de insumos');
        }
    });

    $("#modalMarcaForm").submit(function(e){
        e.preventDefault();
        agregarMarca()
    });

    $("#btnAgregarCategoria").click(function(){
        $('#modalCategoria').modal("show");
    });
    $("#btnAgregarMarca").click(function(){
        $('#modalMarca').modal("show");
    });

});

function calcularTotal() {
    var total = 0;
    $(productos).each(function (i, o) {
        if ($(`#check_${o.id}`).prop('checked')) {
            var cant = parseFloat($(`#cant_${o.id}`).val());
            if (cant == 0) {
                $(`#cant_${o.id}`).val(1);
                cant = 1;
            }

            var cost = $(`#cost_${o.id}`).val();

            total += cost * cant;
        }
    });

    $('[name=precio_items]').val(parseFloat(total).toFixed(2));

}

function agregarCategoria() {
    var nombre  =   $("#modalCategoriaForm [name=nombre]").val();
    var descripcion =   $("#modalCategoriaForm [name=descripcion]").val();
    var data = $("#modalCategoriaForm").serializeObject();

    $.ajax({
        url     :   base + "item-categorias/guardar-categoria-ajax",
        data: data,

        type    :   'POST',
        success :   function (response) {
            console.log(response);
            $('#modalCategoria').modal("hide");
            fillCategorias(response.data.id);
        },error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(xhr);
            console.log(thrownError);
        }
    });
    return false;
}


function agregarMarca() {
    var nombre = $("#modalMarcaForm [name=nombre]").val();
    $.ajax({
        url     :   base + "item-marcas/guardar-json-ajax",
        data    : { id:'', nombre: nombre},
        type    :   'POST',
        // dataType:   'JSON',
        success :   function (response) {
            $('#modalMarca').modal("hide");
            fillMarcas()
        },error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
    return false;
}

function agregarTipoTributo() {
    var tipo_tributo_codigo=$("input[name=tipo_tributo_codigo]").val();
    var tipo_tributo_descripcion=$("input[name=tipo_tributo_descripcion]").val();
    var tipo_tributo_tax_duti_nombre=$("input[name=tipo_tributo_tax_duti_nombre]").val();
    var tipo_tributo_tax_duti_categoria=$("input[name=tipo_tributo_tax_duti_categoria]").val();
    $.ajax({
        url     :   base + "mercaderia/ajax-save-tipo-tributo",
        data    : {tipo_tributo_codigo:tipo_tributo_codigo,tipo_tributo_descripcion:tipo_tributo_descripcion,tipo_tributo_tax_duti_nombre:tipo_tributo_tax_duti_nombre,tipo_tributo_tax_duti_categoria: tipo_tributo_tax_duti_categoria},
        type    :   'GET',
        // dataType:   'JSON',
        success :   function (response) {
            $('#modalTipoTributo').modal("hide");
            $("div[name=divtipotributo]").load(" div[name=divtipotributo]");
        },error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function fillCategorias(id){

    $.ajax({
        url     :   base + "item-categorias/get-list",
        data    : { },
        type    :   'GET',
        // dataType:   'JSON',
        success :   function (response) {

            var options = "<option value=''> -- Seleccione -- </option>"
            $(response).each(function(i, o){
                options += `
                <option value='${o.id}'>${o.nombre}</option>
                `;
            })
            $("[name=categoria_id]").html(options)
            $("[name=categoria_id]").val(id);

            console.log(response)
        },error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
    });


}

function fillMarcas(){

    $.ajax({
        url     :   base + "item-marcas/get-list",
        data    : { },
        type    :   'GET',
        // dataType:   'JSON',
        success :   function (response) {

            var options = "<option value=''> -- Seleccione -- </option>"
            $(response).each(function(i, o){
                options += `
                <option value='${o.id}'>${o.nombre}</option>
                `;
            })
            $("[name=marca_id]").html(options)

            console.log(response)
        },error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
    });


}

function addProducto() {
    $('[name=codigo_producto]').val('');
    $('[name=nombre_producto]').val('');
    $('[name=precio_producto]').val('');
    $("#productoModal").modal("show");
}

function guardarProducto(){
    $.ajax({
        url: base + "items/guardar-producto",
        data: {
            'nombre': $("[name=nombre_producto]").val(),
            'precio_producto': $("[name=precio_producto]").val(),
            'codigo_producto': $("[name=codigo_producto]").val()
        },
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            //console.log(data)
            $("#productoModal").modal("hide");
            $("[name=nombre_producto]").val('');
            $("[name=precio_producto]").val('');
            $("[name=codigo_producto]").val('');

            var parte = `
            <div class="filtrar">
                    <div class="row w-100 py-1  align-items-end" style="min-width:600px">
                        <div class="col-sm-4 col-md-6 col-lg-6">
                            <label> <input id="check_${data.id}" type="checkbox" name="productos[${data.id}]" onchange="calcularTotal()" /> ${data.nombre} </label>
                        </div>

                        <div class="col-sm-4 col-md-3 col-lg-3">
                            Precio: S/. <input type="number" step="any" min="0" style="width: 120px;" name="costos[${data.id}]" value="${data.precio_venta}" id="cost_${data.id}" onchange="calcularTotal()" autocomplete="off">
                        </div>

                        <div class="col-sm-4 col-md-3 col-lg-3">
                            Cantidad: <input type="number" step="any" min="0" style="width: 120px;" name="cantidades[${data.id}]" value="0" id="cant_${data.id}" onchange="calcularTotal()" autocomplete="off">
                        </div>
                        <p> </p>
                    </div>
                </div>
            `;

            productos.push({
                id: data.id,
                precio_venta: data.precio_venta,
                nombre: data.nombre,
                codigo: data.codigo
            });

            $(`#listadoProductos`).append(parte);

        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

(function () {
    jQuery.expr[':'].containsNC = function (elem, index, match) {
        return (elem.textContent || elem.innerText || jQuery(elem).text() || '').toLowerCase().indexOf((match[3] || '').toLowerCase()) >= 0;
    }
}(jQuery));


$("#categoria_autocomplete").on("keyup", function () {
    var ttt = $("#categoria_autocomplete").val();
    if (ttt == '') {
        $(".filtrar").css({"display": "block"})
    } else {
        $(".filtrar").css({"display": "none"})
        $(`.filtrar:containsNC(${ttt})`).css("display", "block");
    }
})

