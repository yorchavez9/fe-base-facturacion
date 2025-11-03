<div class="modal fade" id="modalCertificado" tabindex="0" aria-labelledby="modalAlertaLabel" aria-hidden="true" <?= $estado_certificado == 'VENCIDO' ? 'data-backdrop="static"' : '' ?>>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="d-flex align-items-center modal-body alert <?= $estado_certificado == 'VENCIDO' ? 'alert-danger' : 'alert-info' ?> mb-0 modal_text" role="alert">
        <?php
        if ($estado_certificado == 'VENCIDO') {
          echo "<p>Su certificado digital esta vencido, contacte con el administrador.</p>";
        } else {
          echo "<p>Su certificado digital esta por vencer, actualícelo.</p>";
        }
        ?>
        <?php if ($estado_certificado != 'VENCIDO'): ?>
          <div class="ml-auto">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->Html->scriptBlock(" var estado_certificado = '" . $estado_certificado . "';" ); ?>
<script>
  $(document).ready(function() {
    var alertaCert = window.sessionStorage.getItem('alertaCert');
    if(estado_certificado == 'VENCIDO'){
      $("#modalCertificado").modal('show');
    }else if(estado_certificado != 'ACTIVO'){
      if (!alertaCert == '1') {
        $("#modalCertificado").modal('show');
        window.sessionStorage.setItem('alertaCert', '1');
      }
    }
  });
</script>
<style>
  .modal_text {
    font-size: 20px;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  }
</style>