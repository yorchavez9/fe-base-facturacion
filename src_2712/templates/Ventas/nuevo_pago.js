$(document).ready(function(){
    
    $("[name=fecha_pago]").datepicker({
        locale: 'es-es',
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4'
    });
    $("[name=monto]").mask("#00.00",{reverse:true});
});