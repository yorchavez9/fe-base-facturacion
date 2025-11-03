$(document).ready(function () {
    Series.getData();
    $("[name=correlativo]").mask("#")
});

var Series = {
    modalId: '#modalSeries',
    getData: function () {
        var endpoint = `${API_FE}/sunat-fe-series`;
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
                                <td>${e.tipo}</td>
                                <td> 
                                     ${e.serie}
                                </td>
                                <td> ${e.correlativo}</td>
                                <td class="actions" width="150px">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href='#' class='dropdown-item' onclick='Series.edit("${e.id}")'> <i class='fas fa-pencil-alt fa-fw'></i> Editar</a>
                                            <a href='#' class='dropdown-item' onclick='Series.delete("${e.id}")'> <i class='fas fa-trash-alt fa-fw'></i> Eliminar</a>
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
        Series.clearForm();
        Series.getEstablecimientos()
        $("#modalSeries").modal("show");
    },
    save: function (e) {
        e.preventDefault();
        var endpoint = `${API_FE}/sunat-fe-series/save`;
        var form = new FormData(document.getElementById("formSeries"));
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
                    $(Series.modalId).modal("hide");
                    Series.getData();
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
        Series.clearForm();
        Series.getOne(id);
        $("#modalSeries").modal("show");
    },
    clearForm: function () {
        $("[name=tipo]").val("");
        $("[name=serie]").val("");
        $("[name=correlativo]").val("");
        $("[name=id]").val(0);
        $('.check-establecimientos').each(function(i, e) {
            $(e).prop('checked', false)
        });
    },
    setData: function (data) {
        $("[name=tipo]").val(data.tipo);
        $("[name=serie]").val(data.serie);
        $("[name=correlativo]").val(data.correlativo);
        $("[name=id]").val(data.id);
        var arr = data.establecimientos.map((e)=>{
            return `${e.id}`
        });
        Series.getEstablecimientos( arr )
    },
    getOne: function (id = 0) {
        var endpoint = `${API_FE}/sunat-fe-series/get-one/${id}`;
        $.ajax({
            url: endpoint,
            type: "GET",
            success: function (r) {
                if(r.success){
                    Series.setData(r.data)
                }
            },
        });
    },
    delete: function (id = 0) {
        var endpoint = `${API_FE}/sunat-fe-series/delete/${id}`;
        $.ajax({
            url: endpoint,
            type: "GET",
            success: function (r) {
                alert(r.message)
                if(r.success){                    
                    Series.getData()
                }
            },
        });
    },
    getEstablecimientos: function ( array = []) {
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
                        <div>
                            <input id="ch_${e.id}" type="checkbox" value="${e.id}" class="check-establecimientos" name="establecimientos[]">
                            <label for="ch_${e.id}">  ${e.nombre} </label>
                        </div>
                        `;
                    });
                    $("#list_establecimientos").html(str);
                }

                $('.check-establecimientos').each(function(i, e) {
                    if( array.includes( $(e).val() ) ){
                        $(e).prop('checked', true)
                    }
                });
            },
        });
    },
};
