<div class="modal fade" id="modalSerieCorrelativo" tabindex="-1" role="dialog" aria-labelledby="modalSerieCorrelativoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSerieCorrelativoLabel"> Ingrese el recibo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <input type="text" name="factura" placeholder="####-########" class="form-control form-control-sm text-center" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <a href="javascript:ModalFacturaNota.cargarFactura()" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-check"></i> Cargar Factura </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var ModalFacturaNota = {
    tipo: '',
    init: function (tipo= '') {
        ModalFacturaNota.tipo = tipo;
        $("#modalSerieCorrelativo").modal('show')
        $("#modalSerieCorrelativo").on('hide.bs.modal', () =>{
            var num_factura = $("#modalSerieCorrelativo [name=factura]").val("");
        })
        $("#modalSerieCorrelativo").on('shown.bs.modal', () =>{
            $("#modalSerieCorrelativo [name=factura]").focus();
        })

    },
    cargarFactura: function ( ) {
        var num = $("#modalSerieCorrelativo [name=factura]").val();
        var endpoint = `${base}ventas/api-venta-for-nota`;
        $.ajax({
            url     :   endpoint,
            data    :   { documento : num },
            type    :   'POST',
            success :   function (data, status, xhr) {// success callback function
                console.log(data);
                if(data.success)
                {
                    location.href = `${base}sunat-fe-notas-${ModalFacturaNota.tipo}/nota-${ModalFacturaNota.tipo}/${data.data.id}` ;
                }else{
                    alert(data.message);
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
}
</script>
