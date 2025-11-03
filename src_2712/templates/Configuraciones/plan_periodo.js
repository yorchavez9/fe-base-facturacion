$(document).ready(function () {
    llenarListados();
    verificarPlan()
    $("[name=fe_renovacion]").on('change', () => {
        verificarPlan()
    })
});

function llenarListados () {
    var optDias = '';
    for (let index = 1; index < 31 ; index++) {
        optDias += `<option val='${index.toString().padStart(2, '0')}'> ${index} </option>`;
    }
    $("[name=fe_dia_renovacion]").html(optDias)
    $("[name=fe_fecha_inicio]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
}
function verificarPlan () {
    if ( $("[name=fe_renovacion]").val() == 'MENSUAL' ){
        $("#div_dia_renovacion").attr('hidden', false);
        $("#div_mes_renovacion").attr('hidden', true);
        $("#div_periodo_gracia").attr('hidden', false);
    } else if( $("[name=fe_renovacion]").val() == 'ANUAL' ){
        $("#div_dia_renovacion").attr('hidden', false);
        $("#div_mes_renovacion").attr('hidden', false);
        $("#div_periodo_gracia").attr('hidden', false);
    } else if( $("[name=fe_renovacion]").val() == 'LIFETIME' ){
        $("#div_dia_renovacion").attr('hidden', true);
        $("#div_mes_renovacion").attr('hidden', true);
        $("#div_periodo_gracia").attr('hidden', true);
    }
    $("[name=fe_dia_renovacion]").val('')
    $("[name=fe_mes_renovacion]").val('')
    $("[name=fe_periodo_gracia]").val('')
    getConfigs(['fe_dia_renovacion', 'fe_mes_renovacion', 'fe_periodo_gracia'])
}
function getConfigs( variables = [] ) {
    var endpoint = `${base}configuraciones/get-multiple-var`;
    $.ajax({
        url: endpoint,
        type: 'POST',
        data: { vars: variables },
        success: function (r) {
            if(r.success){
                console.log(r)
                $("[name=fe_dia_renovacion]").val(r.data['fe_dia_renovacion'])
                $("[name=fe_mes_renovacion]").val(r.data['fe_mes_renovacion'])
                $("[name=fe_periodo_gracia]").val(r.data['fe_periodo_gracia'])
            }
        }
    });
}
