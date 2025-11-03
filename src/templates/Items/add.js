$(document).ready(function(){
    $('.celular').mask('000-000-000');
    $('.telefono').mask('00-00-00');
    $('.whatsaap').mask('+00 000000000');
    $('.dni').mask('00000000');
    $('.ruc').mask('00000000000');
    $('.letras').mask('Z',{translation: {'Z': {pattern: /[a-zA-Z ]/, recursive: true}}});
    $('.numeros').mask('Z',{translation: {'Z': {pattern: /[0-9]/, recursive: true}}});
    $('.email').mask('ZZZZZZZZZZZZZZZ@YYYYYYYYYY',{translation: {'Z': {pattern: /^[a-zA-Z0-9]$/, recursive: true},'Y': {pattern: /[a-zA-Z]/, recursive: true},'X': {pattern: /[a-zA-Z]/, recursive: true}}});
    $('.fecha').mask('00/00/0000');
    $('.hora').mask('00:00:00');
    $('.gremision').mask('F000000');

    jQuery.expr[':'].icontains = function(a, i, m) {
        return jQuery(a).text().toUpperCase()
            .indexOf(m[3].toUpperCase()) >= 0;
      };

    $("#modalCategoriaForm").submit(function(e){
        e.preventDefault()
        agregarCategoria();
    });
    $("#modalMarcaForm").submit(function(e){
        e.preventDefault();
        agregarMarca()
    });

    $("input[name=filtrocats]").keyup(function(){
        var txt = $("input[name=filtrocats]").val();
        console.log(txt);
        $(".checkbox label").parent().hide();
        $(".checkbox label:icontains("+ txt.toLowerCase() +")").parent().show();
    });
    $("#btnAgregarCategoria").click(function(){
        $('#modalCategoria').modal("show");
    });
    $("#btnAgregarMarca").click(function(){
        $('#modalMarca').modal("show");
    });
    $("#btnAgregarTipoTributo").click(function(){
        $('#modalTipoTributo').modal("show");
    });
});

function agregarCategoria() {
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
    var data = $("#modalMarcaForm").serializeObject();
    $.ajax({
        url     :   base + "item-marcas/guardar-json-ajax",
        //data    : { id:'', nombre: nombre},
        type    :   'POST',
        data: data,
        // dataType:   'JSON',
        success :   function (response) {
            $('#modalMarca').modal("hide");
            fillMarcas(response.data.id);
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

function fillMarcas(id){

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
            $("[name=marca_id]").val(id)

            console.log(response)
        },error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
    });


}

function generarCodigo(n = 8){
    var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ2346789@$";
    var pwd = "";
    for (i = 0; i < n; i++) pwd += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    $("[name=codigo]").val(pwd);
}
