$(document).ready(function () {
    /* código para lanzar el modal */
    $("#btnNuevo").click(function(e){
        e.preventDefault();
        $('#modalMarca').modal('show')
    });

    /* código para guardar los datos de la categoria */
    $("#modalMarcaForm").submit(function(e){
        e.preventDefault();
        marcaGuardar();
    });

    /* código para lanzar el modal cuando se edita una categoria */
    $(".link_editar").click(function(e){
        e.preventDefault();
        var id = $(this).attr("data-id");
        marcaModificar(id);
    });

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

    // opcion de seleccionar todo
    $('.seleccionar_todo').change(function() {
        var checkboxes = $('.seleccion');
        checkboxes.prop('checked', $(this).is(':checked'));
        $('.seleccionar_todo').prop('checked',$(this).is(':checked'));
    });



});



function marcaModificar(id){

    $.ajax({
        url: base + `item-marcas/get-json/${id}`,
        type:   'GET',
        dataType: 'JSON',
        success : function(r){
            $('#modalMarca').modal('show')
            $('#modalMarcaForm [name=id]').val(r.id)
            $('#modalMarcaForm [name=nombre]').val(r.nombre)
            $('#modalMarcaForm [name=orden]').val(r.orden)
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus)
            alert(errorThrown)

        }
    });
}

function marcaGuardar(){

    var data = $("#modalMarcaForm").serializeObject();

    $.ajax({
        url: base + "item-marcas/guardar-json-ajax",
        type:   'POST',
        dataType: 'JSON',
        data: data,
        success : function(r){
            console.log(r);
            if(r.success){
                location.reload();
            }else{
                alert(r.data)
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus)
            alert(errorThrown)

        }
    });

}
function deleteAll(){

    var r = confirm("¿Realmente desea eliminar los registros seleccionados?");
    if (!r){return false;}

    var ids = new Array()
    $('.seleccion:checked').each(function(i, o){
        ids.push(o.value);
    });

    $.ajax({
        url: base + 'item-marcas/borrado-masivo',
        data: {checks : ids},
        cache: false,
        method: 'POST',
        success: function(data){
            //alert(data);
            location.reload();
            //alert(data);
        }
    });

}


function orderAll(){



    var ids = new Array()
    $('.selorder').each(function(i, o){
        ids.push({
            id : $(o).attr("data-id"),
            orden: o.value
        });
    });

    $.ajax({
        url: base + 'item-marcas/ordenamiento-masivo',
        data: {items : ids},
        cache: false,
        method: 'POST',
        success: function(data){
            // alert(data);
            location.reload();
        }
    });

    return false;

}

