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

    $("#btnAgregarCategoria").click(function(){
        $('#modalCategoria').modal("show");
    });
    $("#btnAgregarMarca").click(function(){
        $('#modalMarca').modal("show");
    });

    $("input[name=filtrocats]").keyup(function(){

        var txt = $("input[name=filtrocats]").val();
        console.log(txt);
        $(".checkbox label").parent().hide();
        $(".checkbox label:icontains("+ txt.toLowerCase() +")").parent().show();

    });

});


function agregarCategoria() {
    var nombre  =   $("#modalCategoriaForm [name=nombre]").val();
    var descripcion =   $("#modalCategoriaForm [name=descripcion]").val();

    $.ajax({
        url     :   base + "item-categorias/guardar-categoria-ajax",
        data    : {id:'', nombre: nombre, descripcion: descripcion },
        type    :   'POST',
        // dataType:   'JSON',
        success :   function (response) {
            $('#modalCategoria').modal("hide");
            fillCategorias();
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


function fillCategorias(){

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
