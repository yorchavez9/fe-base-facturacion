$(document).ready(function(){

    // detectamos cuando dan clic a cualquier clase .clic_editar
    $(".click_editar").click(function(){

        // extraemos el canal id leyendo el atributo data-id
        var canalid = $(this).attr("data-id");
        FormCuentaTipo.Editar(canalid)
    });

    $(".click_eliminar").click(function(){

        // extraemos el canal id leyendo el atributo data-id
        var canalid = $(this).attr("data-id");
        eliminar(canalid)

    });

    $("#btnNuevo").click(function(e){
        e.preventDefault()
        FormCuentaTipo.Nuevo();
    });

    FormCuentaTipo.setEventoGuardar(function(){
        location.reload();
    })

});

function eliminar(id){

    var r = confirm("¿Seguro que desea eliminar éste registro ?");
    if (!r){return false};

    var endpoint = `${base}cuenta-tipos/del`;
    $.ajax({
        url: endpoint,
        data: {
            ids: [id]
        },
        type:'POST',
        success : function(r){
            console.log(r);
            location.reload()

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }

    });
}