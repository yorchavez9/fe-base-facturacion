var _contactos = [];
var _contacto_index = 0;
var _index = 0;
$(document).ready(function () {

    FormPersona.Init("get-json");
    FormPersona.callBack = function(data){
        contactoGuardar(data)
        FormPersona.Cerrar();
    }

    $('#formCuentasAdd').submit(function(e){

        var doc_nro = $('input[name=ruc]').val();
        if(doc_nro.length > 0 && doc_nro.length < 11){
            alert('El documento de identidad debe de tener 11 dígitos');
            e.preventDefault();
        }


    })

    $('.celular').mask('000-000-000');
    $('.telefono').mask('(000)-000-000');
    $('.fecha').mask('00/00/0000');
    $('.hora').mask('00:00:00');

    $('[name=persona_dni]').mask('00000000');
    $('[name=ruc]').mask('00000000000');
    $('[name=celular]').mask('000000000000');
    $('[name=telefono]').mask('000000000000');
    $('[name=whatsapp]').mask('000000000000');

    // buscador de clientes
    $("#buscador").submit(function (e) {

        e.preventDefault();
        $("#modalBuscador").modal("show");
        buscarCoincidencias();  // lanzamos el modal con coincidencias

    });


    $('input[name=ubigeo_dpr]').autocomplete({
        minChars: 0,
//        paramName       : 'busqueda',
//        serviceUrl      : _base + 'busqueda/autocomplete/ubicaciones',
        autoSelectFirst: true,
        showNoSuggestionNotice: 'Sin resultados',
        lookup: ubicaciones,
        onSelect: function (suggestion) {
            console.log(suggestion);
            $("input[name=ubigeo]").val(suggestion.id);
        },
        onSearchComplete: function (query, suggestions) {
        },
        onHint: function (hint) {
            $('#autocomplete-ajax-x').val(hint);
        },
        onInvalidateSelection: function () {
            $('#selction-ajax').html('You selected: none');
        }
    });


    // listamos los contactos
    contactoListar();

    // enlazamos el evento
    $("#contactoModalForm").submit(function (e) {
        e.preventDefault();
        $("#contactoGuardarBtnGuardar").click();
    });

});

function buscarCoincidencias() {

    var busqueda = $("[name=busqueda]").val();

    $.ajax({
        url: base + "clientes/ajax-buscar-coincidencias",
        data: {b: busqueda},
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function

            var html = "";

            $(data).each(function (i, cli) {
                html += "<tr>";
                html += "<td>" + cli.ruc + "</td>";
                html += "<td>" + cli.razon_social + "</td>";
                html += "<td>" + cli.nombre_comercial + "</td>";
                html += "<td><label style='cursor:pointer;' onclick='redirectCliente(\"" + cli.id + "\")' ><i class='fa fa-pencil fa-fw'></i> Editar</label></td>";
                html += "</tr>";
            });

            $("#modalBuscador .modal-body table tbody").html(html);
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function redirectCliente(id) {
    location.href = base + "clientes/edit/" + id;
}


function consultaDniAjax() {

    var dni = $('input[name=persona_dni]').val();
    $(".estado_busqueda1").html("<i class='fa fa-spinner fa-spin'></i>");

    $.ajax({
        url: base + "sunat-fe/api-consulta-dni-ruc",
        data: {'documento': dni},
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            $('input[name=persona_dni]').val(dni);
            $('input[name=persona_nombres]').val(data.result.nombres);
            $('input[name=persona_apellidos]').val(data.result.apellidos);
            $(".estado_busqueda1").html("<i class='fa fa-check fa-fw'></i>");
        }, error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
        }
    });
}
function consultaRucAjax() {

    var ruc = $('input[name=ruc]').val();
    $.ajax({
        url: base + "cuentas/find-cuenta-by-ruc",
        data: {'ruc': ruc},
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
           if (data=="error"){
               $("#guardarBtn").prop("disabled",false)
           }else{
               alert("El ruc o dni ya existe en nuestra base de datos, seleccione otro")
               $("#guardarBtn").prop("disabled",true)
           }
        }, error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);

        }
    });
    $(".estado_busqueda2").html("<i class='fa fa-spinner fa-spin fa-fw'></i>");
    $.ajax({
        url: base + "sunat-fe/api-consulta-dni-ruc",
        data: {'documento': ruc},
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            $('input[name=ruc]').val(ruc);
            $('input[name=nombre_comercial]').val(data.result.nombre_comercial);
            $('input[name=razon_social]').val(data.result.razon_social);
            $('input[name=domicilio_fiscal]').val(data.result.direccion);
            $(".estado_busqueda2").html("<i class='fa fa-check fa-fw'></i>");
        }, error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
        }
    });
}

function copiarNombreCompleto(){

    var nom = $("[name=persona_nombres]").val();
    var ape = $("[name=persona_apellidos]").val();
    $("[name=nombre_comercial]").val(`${nom} ${ape}`);

}

function contactoNuevo() {
    $("#contactoModal").modal("show");
    GlobalFormPersona.limpiar();
}

function contactoListar() {

    var json = JSON.stringify(_contactos);
    $("#json_contactos_input").val(json);
    contactoGenerarTabla(_contactos);

}

function contactoGuardar(cc) {
    console.log(cc);
    if (cc.id == '') {
        cc.id = _contacto_index;
        _contactos.push(cc);
        _contacto_index++;
    } else {
        _contactos2 = [];
        console.log(_contactos);
        $(_contactos).each(function (i, c) {
            if (c.id != cc.id) {
                _contactos2.push(c);
            } else {
                _contactos2.push(cc);
            }
        })
        _contactos = _contactos2
    }

    contactoListar();
}

function contactoGenerarTabla(json) {
    var html = "";
    $(json).each(function (i, c) {
        html += "<tr>";
        html += "<td>" + c.dni + "</td>";
        html += "<td>" + c.nombres + "</td>";
        html += "<td>" + c.apellidos + "</td>";
        html += "<td>" + c.cargo_empresa + "</td>";
        html += "<td>" + c.telefono + "</td>";
        html += "<td>" + c.whatsapp + "</td>";
        html += "<td>";
        html += "<span onclick='contactoEditar(\"" + c.id + "\")'><i class='fas fa-pencil-alt fa-fw'></i></span>";
        html += "<span onclick='contactoEliminar(\"" + c.id + "\")'><i class='fas fa-trash fa-fw'></i></span>";
        html += "</td>";
        html += "</tr>";
    });
    $("#tablaContactos").html(html);
}


function contactoEditar(cid) {

    $(_contactos).each(function (i, c) {
        if (c.id == cid) {

            var data = c;
            FormPersona.Abrir();
            FormPersona.setDatos(data);
        }
    });


}

function contactoEliminar(cid) {

    _contactos2 = [];
    console.log(_contactos);
    $(_contactos).each(function (i, c) {
        if (c.id != cid) {
            _contactos2.push(c);
        }
    })

    _contactos = _contactos2
    contactoListar();
}


function addCanalLlegada() {
    $("#canalLlegadaModal").modal("show");
}

function guardarCanalLlegada() {
    $.ajax({
        url: base + "cuentas/add-canal-llegada",
        data: {'nombre': $("[name=canal_llegada]").val()},
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function

            html=`<option value="${data.id}">${data.nombre}</option>`
            $("[name=canal_llegada_id]").append(html)
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
    $("#canalLlegadaModal").modal("hide");
}

function addTipoCliente() {

    $("#tipoClienteModal").modal("show");
}

function guardarTipoCliente() {
    $.ajax({
        url: base + "cuentas/add-tipo-cliente",
        data: {'nombre': $("[name=tipo_cliente]").val()},
        type: 'GET',
        dataType: 'JSON',
        success: function (data, status, xhr) {// success callback function
            console.log(data)
            html=`<option value="${data.id}">${data.nombre}</option>`
            $("[name=cliente_tipo_id]").append(html)
        }, error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
    $("#tipoClienteModal").modal("hide");
}
