function verResumenEvento(eventojson){
    //console.log(eventojson);
    $("#modalEventoCliente").modal("show");
    $("#modalEventoCliente .modal-body").html(eventojson.contenido);

}