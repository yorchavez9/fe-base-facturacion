
$(document).ready(function(){

    // opcion de seleccionar todo
    $('.seleccionar_todo').change(function() {
        var checkboxes = $('.seleccion');
        checkboxes.prop('checked', $(this).is(':checked'));
        $('.seleccionar_todo').prop('checked',$(this).is(':checked'));
    });

});



function deleteAll(){

    var r = confirm("¿Realmente desea eliminar los registros seleccionados?");
    if (!r){return false;}

    var ids = new Array()
    $('.seleccion:checked').each(function(i, o){
        ids.push(o.value);
    });

    $.ajax({
        url: base + 'items/borrado-masivo',
        data: {checks : ids},
        cache: false,
        method: 'POST',
        success: function(data){
            // alert(data);
            console.log(data)
            location.reload();
            //alert(data);
        }
    });

}
