function limpiarSistema() {
    var endpoint = `${base}configuraciones/clear-system`;
    var opt = confirm('¿Está seguro que desea eliminar todos los datos generados?');

    if (!opt) {
        return;
    }

    $.ajax({
        url: endpoint,
        type: 'POST',
        data: '',
        success: function (r) {
            console.log(r);
            alert(r.message);
        }
    });
}

function limpiarClientes() {
    var endpoint = `${base}configuraciones/clear-clientes`;
    var opt = confirm('¿Está seguro que desea eliminar todos los datos de los clientes?');

    if (!opt) {
        return;
    }

    $.ajax({
        url: endpoint,
        type: 'POST',
        data: '',
        success: function (r) {
            console.log(r);
            alert(r.message);
        }
    });
}

function limpiarDirectorio() {
    var endpoint = `${base}configuraciones/clear-directorio`;
    var opt = confirm('¿Está seguro que desea eliminar todos los directorios?');

    if (!opt) {
        return;
    }

    $.ajax({
        url: endpoint,
        type: 'POST',
        data: '',
        success: function (r) {
            console.log(r);
            alert(r.message);
        }
    });
}

function limpiarProductos() {
    var endpoint = `${base}configuraciones/clear-productos`;
    var opt = confirm('¿Está seguro que desea eliminar todos los productos?');

    if (!opt) {
        return;
    }

    $.ajax({
        url: endpoint,
        type: 'POST',
        data: '',
        success: function (r) {
            console.log(r);
            alert(r.message);
        }
    });
}

function limpiarDocumentosElectronicos() {
    var endpoint = `${base}configuraciones/clear-documentos-electronicos`;
    var opt = confirm('¿Está seguro que desea eliminar todos los documentos electrónicos?');

    if (!opt) {
        return;
    }

    $.ajax({
        url: endpoint,
        type: 'POST',
        data: '',
        success: function (r) {
            console.log(r);
            alert(r.message);
        }
    });
}

function enviarMensajePrueba(e){
    e.preventDefault();

    var data = {message:$('#whatsapp_mensaje_prueba').val(),number:$('#whatsapp_numero_prueba').val()};
    // console.log(data);
    // return;
    $.ajax({
        url:`${base}whatsapp/send-msg`,
        data: data,
        type:'POST',
        success: function (data) {
            console.log(data);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}
function enviarCorreoPrueba(e){
    e.preventDefault();
    var data = {mensaje:$('#correo_prueba_mensaje').val(),correo_destino:$('#correo_prueba_destino').val()};
    $.ajax({
        url:`${base}configuraciones/send-mail-test`,
        data: data,
        type:'POST',
        success: function (data) {
            console.log(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}
