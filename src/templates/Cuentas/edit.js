var _contactos = [];
var _contacto_index = 0;
var _index = 0;
$(document).ready(function(){

    FormPersona.Init("save");
    FormPersona.callBack = function(data){
        FormPersona.Cerrar();
        // listamos los contactos
        contactoListar();
    }

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
    $("#buscador").submit(function(e){

        e.preventDefault();
        $("#modalBuscador").modal("show");
        buscarCoincidencias();  // lanzamos el modal con coincidencias

    });


    $('input[name=ubigeo_dpr]').autocomplete({
        minChars        : 0,
//        paramName       : 'busqueda',
//        serviceUrl      : _base + 'busqueda/autocomplete/ubicaciones',
        autoSelectFirst : true,
        showNoSuggestionNotice : 'Sin resultados',
        lookup          : ubicaciones,
        onSelect        : function(suggestion) {
            console.log(suggestion);
            $("input[name=ubigeo]").val(suggestion.id);
        },
        onSearchComplete   : function(query, suggestions){
        },
        onHint: function (hint) {
            $('#autocomplete-ajax-x').val(hint);
        },
        onInvalidateSelection: function() {
            $('#selction-ajax').html('You selected: none');
        }
    });

    $("#contactoGuardarBtnGuardar").click(function (){
        var cc = $("#contactoModal form").serializeObject();
        var cliente_id = $("input[name=cliente_id]").val();
        cc.cliente_id = cliente_id;
        contactoGuardar(cc);
        $("#contactoModal").modal("hide");

    });

   // $('.dni').mask('00000000');
   // $('.ruc').mask('00000000000');
    //$('.numeros').mask('Z',{translation: {'Z': {pattern: /[0-9]/, recursive: true}}});


    // listamos los contactos
    contactoListar();
});

function buscarCoincidencias(){

    var busqueda = $("[name=busqueda]").val();

    $.ajax({
        url     :   base + "clientes/ajax-buscar-coincidencias",
        data    :   {b : busqueda},
        type    :   'GET',
        dataType:   'JSON',
        success :   function (data, status, xhr) {// success callback function

            var html = "";

            $(data).each(function(i, cli){
                html += "<tr>";
                html += "<td>" + cli.ruc + "</td>";
                html += "<td>" + cli.razon_social + "</td>";
                html += "<td>" + cli.nombre_comercial + "</td>";
                html += "<td><label style='cursor:pointer;' onclick='redirectCliente(\""+cli.id+"\")' ><i class='fa fa-pencil fa-fw'></i> Editar</label></td>";
                html += "</tr>";
            });

            $("#modalBuscador .modal-body table tbody").html(html);
        },error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function redirectCliente(id){
    location.href = base + "clientes/edit/" + id;
}


function contactoListar(){
    var cliente_id = $("input[name=cliente_id]").val();
    $.ajax({
        url     :   base + "cuentas/contacto-listar",
        data    :   {cliente_id : cliente_id},
        type    :   'GET',
        dataType:   'JSON',
        success :   function (data, status, xhr) {// success callback function

            console.log('Lista contactos');
            console.log(data);
            contactoGenerarTabla(data);
        },error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}


function contactoGenerarTabla(json){
    var html = "";
    $(json).each(function(i,c){
        html += "<tr>";
        html += "<td>"+ c.dni +"</td>";
        html += "<td>"+ c.nombres +"</td>";
        html += "<td>"+ c.apellidos +"</td>";
        html += "<td>"+ c.cargo_empresa +"</td>";
        html += "<td>"+ c.telefono +"</td>";
        html += "<td>"+ (c.whatsapp ?? "") +"</td>";
        html += "<td>";
        html += "<span onclick='contactoEditar(\""+c.id+"\")'><i class='fas fa-pencil-alt fa-fw'></i></span>";
        html += "<span onclick='contactoEliminar(\""+c.rel_id+"\")'><i class='fas fa-trash fa-fw'></i></span>";
        html += "</td>";
        html += "</tr>";
    });
    $("#tablaContactos").html(html);
}

function contactoEditar(cid){
    FormPersona.Editar(cid);
}

function contactoEliminar(cid){
    var rr = confirm("¿Realmente desea eliminar este contacto?");
    if (!rr){
        return;
    }
    $.ajax({
        url     :   base + "cuentas/contacto-eliminar",
        data    :   {rel_id:cid},
        type    :   'GET',
        // dataType:   'JSON',
        success :   function (data, status, xhr) {// success callback function
            contactoListar();
        },error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function consultaDniRucAjax(){

    var ruc = $('input[name=ruc]').val();
    $(".estado_busqueda").html("Consultando ... <i class='fa fa-spinner fa-spin'></i>");
    $.ajax({
        url     :   base + "sunat-fe/api-consulta-dni-ruc",
        data    :   {'documento' : ruc},
        type    :   'GET',
        dataType:   'JSON',
        success :   function (data, status, xhr) {// success callback function
            $('input[name=ruc]').val(ruc);
            $('input[name=nombre_comercial]').val(data.result.nombre_comercial);
            $('input[name=razon_social]').val(data.result.razon_social);
            $('input[name=domicilio_fiscal]').val(data.result.direccion);
            $(".estado_busqueda").html("Volver a Consultar <i class='fa fa-check'></i>");
        },error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });

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
