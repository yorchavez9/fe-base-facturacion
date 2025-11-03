$(document).ready(()    =>  {
    $("#exportar_ventas").click(()  =>  {
        getReport("VENTAS");
    });
    $("#exportar_compras").click(()  =>  {
        getReport("COMPRAS");
    });
    $("#exportar_notas_credito").click(()  =>  {
        getReport("NOTAS_CREDITO");
    });
    $("#exportar_notas_debito").click(()  =>  {
        getReport("NOTAS_DEBITO");
    });
})


function getReport(val){
    $("[name=exportar]").val(val);
    $("[name=exportar]").click();
}