
$(document).ready(function () {
    // cambiar el estado de la visibilidad individualmente
    $(".estado_visibilidad").click(function(e){
        e.preventDefault();
        var estado = $(this).attr("data-visible");
        var itemid = $(this).attr("data-id");

        var formData = new FormData();
        formData.append("estado",estado);
        formData.append("itemid",itemid);
        formData.append("entidad","item");
        formData.append("_csrfToken",_csrfToken);

        $("#item_" + itemid).html("<i class='fa fa-spinner fa-spin fa-spin'><i>");

        $.ajax({
            url: base + 'items/cambiar-estado',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method  : 'POST',
            dataType: 'json',
            success: function(data){
                if(estado == '1'){
                    $("#item_" + itemid).html("<i class='fa fa-eye'></i> Visible");
                    $("#item_" + itemid).attr("data-visible", 0);
                }else{
                    $("#item_" + itemid).html("<i class='fa fa-eye-slash'></i> No Visible");
                    $("#item_" + itemid).attr("data-visible", 1);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    });



    // opcion de seleccionar todo
    $('.seleccionar_todo').change(function() {
        var checkboxes = $('.seleccion');
        checkboxes.prop('checked', $(this).is(':checked'));
        $('.seleccionar_todo').prop('checked',$(this).is(':checked'));
    });

    // cuando se presiona el link de seleccionart odo
    $(".selall").click(function(e){

        // detenemos la ejecucion de javascript
        e.preventDefault();

        // obtenemos el estado si va a estar visibie o invisible
        var accion = $(this).attr("data-accion");
        var estado = $(this).attr("data-visible");
        var formData = new FormData();
        var data = new Array();
        var checkboxes = $('.seleccion:checked');
        $(checkboxes).each(function(i, o){
            data.push(o.value);
        });
        console.log("registros seleccionados:");
        console.log(data);
        formData.append('ids', data);
        formData.append('accion', accion);
        formData.append('estado', estado);
        formData.append("_csrfToken",_csrfToken);

        $.ajax({
            url: base + 'items/acciones-masivas-items',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data){
                location.reload();
                //alert(data);
            }
        });
    });

});
