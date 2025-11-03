$(document).ready(function () {
    $(`#fec_inicio`).datepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
    });
    $(`#fec_fin`).datepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
    });
   
});

var ParteSalida = {
    limpiar(){
        $('#fec_inicio').val('');
        $('#fec_fin').val('');
        $('#form_filtro').submit();
    }
}