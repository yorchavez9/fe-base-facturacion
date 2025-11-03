<div class="modal fade"
     id="formVentaDesdeExcel"
     tabindex="-1" role="dialog"
     aria-labelledby="formVentaDesdeExcelLabel"
     aria-hidden="true">

    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formVentaDesdeExcelLabel">Cargar Items desde Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-left">
                    Cargue los items desde una proforma en excel. <a download href="<?= $this->Url->Build("/downloads/modelo_venta_excel.xlsx")?>" target="_blank"> Descargar un ejemplo </a>
                </p>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-file-excel fa-fw"></i>
                        </div>
                    </div>
                    <input id="formVentaDesdeExcelInput" type="file" class="form-control" />
                    <div class="input-group-append">
                        <button id="formVentaDesdeExcelButton" class="btn btn-sm btn-outline-primary" onclick="FormVentaDesdeExcel.subir()">
                            <i class="fas fa-upload fa-fw"></i> Cargar Items
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var FormVentaDesdeExcel = {

        IdModal: 'formVentaDesdeExcel',

        IdFormulario: 'formVentaDesdeExcelForm',

        callback    : function(){},

        abrir: function(){
            $("#formVentaDesdeExcel").modal("show");
        },

        subir: function(){

            var input = document.getElementById("formVentaDesdeExcelInput").files[0];

            if(typeof  input === 'undefined'){
                /**
                 * TODO, falta validar si es excel ya sea aqui o en el servidor
                 */
                alert("Por favor, adjunte un archivo excel")
                return false;
            }

            var fdata = new FormData();
            fdata.append("excel", input)

            $("#formVentaDesdeExcelButton").html("<i class='fas fa-spin fa-spinner'></i> Subiendo ...");

            var endpoint = `${base}ventas/venta-desde-excel-ajax`;

            $.ajax({
                url         : endpoint,
                data        : fdata,
                type        : 'POST',
                processData : false,
                cache       : false,
                contentType : false,
                success     : function (r) {
                    FormVentaDesdeExcel.callback(r);
                    $("#formVentaDesdeExcelButton").html("<i class='fa fa-check fa-fw'></i> Venta Cargada");
                },
                error       : function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

        },

    };
</script>
