$(document).ready(function () {
    $('#formConsultar [name=f_inicio]').datepicker({
        format: 'yyyy-mm-dd',
        locale: 'es-es',
        uiLibrary: 'bootstrap4'
    });

    $('#formConsultar [name=f_fin]').datepicker({
        format: 'yyyy-mm-dd',
        locale: 'es-es',
        uiLibrary: 'bootstrap4'
    });
});
