$(document).ready(function () {
    $("#fecha_inicio").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
    });
    $("#fecha_fin").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
    });
    $("#customSwitch1").click(function(){
        if(this.value=='on'){
            console.log('esta on')
        }
    });
});
function modalExportar(id){
    console.log(123)
    modalReporteKardex.Init(id);
    modalReporteKardex.Abrir();
}