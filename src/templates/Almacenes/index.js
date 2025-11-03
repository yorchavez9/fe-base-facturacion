$(document).ready(function () {

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

    $(`[name=codigo]`).mask('0000');
    $(`[name=telefono]`).mask('000000000');

    Establecimientos.getData();
});

var Establecimientos = {
    modalId: '#modalEstablecimiento',
    getData: function () {
        var endpoint = `${API_FE}/establecimientos`;
        $.ajax({
            url: endpoint,
            type: "GET",
            success: function (r) {
                if (r.success) {
                    console.log(r);
                    var str = ``;
                    r.data.forEach((e) => {
                        str += `
                            <tr>
                                <td>${e.codigo}</td>
                                <td> 
                                    <i class="fa fa-building"></i> ${e.nombre}
                                </td>
                                <td> <i class="fa fa-phone"></i> ${e.telefono}</td>
                                <td> <i class="fas fa-mail-bulk"></i> ${e.correo}</td>
                                <td> 
                                    <i class="fas fa-route"></i> ${e.direccion}
                                </td>
                                <td class="actions" width="150px">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href='#' class='dropdown-item' onclick='Establecimientos.edit("${e.id}")'> <i class='fas fa-pencil-alt fa-fw'></i> Editar</a>
                                            <a href='#' class='dropdown-item' onclick='Establecimientos.delete("${e.id}")'> <i class='fas fa-trash-alt fa-fw'></i> Eliminar</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    $("#table_data").html(str);
                }
            },
        });
    },
    add: function () {
        Establecimientos.clearForm();
        $("#modalEstablecimiento").modal("show");
    },
    save: function (e) {
        e.preventDefault();
        var endpoint = `${API_FE}/establecimientos/save`;
        var form = new FormData(document.getElementById("formEstablecimiento"));
        $("#btn_guardar").html(
            "Guardar..." + `<i class="fa fa-spinner fa-spin"></i>`
        );
        $("#btn_guardar").attr("disabled", true);
        $.ajax({
            url: endpoint,
            type: "POST",
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            success: function (r) {
                console.log(r);
                if (r.success) {
                    alert(r.message);
                    $(Establecimientos.modalId).modal("hide");
                    Establecimientos.getData();
                }else{
                    alert(r.message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
            complete: function () {
                $("#btn_guardar").html("Guardar");
                $("#btn_guardar").attr("disabled", false);
            },
        });
    },
    edit: function (id) {
        Establecimientos.clearForm();
        Establecimientos.getOne(id);
        $("#modalEstablecimiento").modal("show");
    },
    delete: function () {},
    clearForm: function () {
        $("[name=codigo]").val("");
        $("[name=nombre]").val("");
        $("[name=telefono]").val("");
        $("[name=correo]").val("");
        $("[name=ubigeo_dpr]").val("");
        $("[name=urbanizacion]").val("");
        $("[name=direccion]").val("");
        $("[name=map_lat]").val("");
        $("[name=map_len]").val("");
        $("[name=ubigeo]").val("");
        $("[name=departamento]").val("");
        $("[name=provincia]").val("");
        $("[name=distrito]").val("");
        $("[name=id]").val(0);
    },
    setData: function (data) {
        $("[name=codigo]").val(data.codigo);
        $("[name=nombre]").val(data.nombre);
        $("[name=telefono]").val(data.telefono);
        $("[name=correo]").val(data.correo);
        $("[name=ubigeo_dpr]").val(data.ubigeo_dpr);
        $("[name=urbanizacion]").val(data.urbanizacion);
        $("[name=direccion]").val(data.direccion);
        $("[name=map_lat]").val(data.map_lat);
        $("[name=map_len]").val(data.map_len);
        $("[name=ubigeo]").val(data.ubigeo);
        $("[name=departamento]").val(data.departamento);
        $("[name=provincia]").val(data.provincia);
        $("[name=distrito]").val(data.distrito);
        $("[name=id]").val(data.id);
    },
    getOne: function (id = 0) {
        var endpoint = `${API_FE}/establecimientos/get-one/${id}`;
        $.ajax({
            url: endpoint,
            type: "GET",
            success: function (r) {
                if(r.success){
                    Establecimientos.setData(r.data)
                }
            },
        });
    },
    delete: function (id = 0) {
        var endpoint = `${API_FE}/establecimientos/delete/${id}`;
        $.ajax({
            url: endpoint,
            type: "GET",
            success: function (r) {
                alert(r.message)
                if(r.success){                    
                    Establecimientos.getData()
                }
            },
        });
    },
};
