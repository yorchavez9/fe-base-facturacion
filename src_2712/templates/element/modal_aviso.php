<div class="modal" tabindex="-1" role="dialog" id="modalAviso" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">AVISO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if(isset($mensaje_aviso)): ?>  
            <p><?=$mensaje_aviso?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


