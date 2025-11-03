<div class="modal fade" id="modalAlerta" tabindex="-1" aria-labelledby="modalAlertaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="d-flex align-items-center modal-body alert alert-info mb-0 modal_text" role="alert">
                Le recomendamos usar el navegador Google Chrome, Edge, Brave u Opera para un correcto funcionamiento del sistema.
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var alerta = window.sessionStorage.getItem('alerta');
        if ( !alerta == '1' ){
            $("#modalAlerta").modal('show');
            window.sessionStorage.setItem('alerta', '1');
        }
    });

</script>
<style>
    .modal_text{
        font-size: 20px;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        /* color: white; */
    }
</style>
