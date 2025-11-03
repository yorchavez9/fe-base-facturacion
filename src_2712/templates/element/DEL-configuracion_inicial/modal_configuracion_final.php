<div class="modal fade" id="modalConfiFinal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Felicidades </label></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <h6> ¡Tu cuenta del Sistema de Cotizaciones se encuentra activa!</h6>
            </div>
        </div>
    </div>
</div>
<script>
var ConfiguracionFinal = {
    id: '#modalConfiFinal',
    init : function ($nombre = '') {
        $(ConfiguracionFinal.id).modal('show');
    },
    getData : function () {

    },
    setData : function ($nombre) {
    },
}

</script>
