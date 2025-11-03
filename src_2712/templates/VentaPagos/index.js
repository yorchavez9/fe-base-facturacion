$(document).ready(function(){

    $("#btnNuevo").click(function(e){
        e.preventDefault();
        ModalPagosVentas.abrir(venta_id, parseFloat(monto_deuda).toFixed(2));
    })
   
})

function pagarProgramado (pago_id= '', monto = 0) {
    ModalPagosVentas.abrir(venta_id, parseFloat(monto).toFixed(2), pago_id);
}