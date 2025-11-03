<div class="modal fade" id="newListadoProductos" 
tabindex="-1" 
role="dialog"  
aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Listado de productos</h5>
        <button onclick="newItemsList.cerrar()" type="button" class="close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= $this->Element('new_list_productos') ?>
      </div>
    </div>
  </div>
</div>

<script>
  var newItemsList = {
    IdModal: 'newListadoProductos',
    close: false,
    callback : null,
    abrir: function(busqueda='') {
      $(`#${newItemsList.IdModal}`).modal('show');
      $(`#${newItemsList.IdModal}`).on('hide.bs.modal', () => {
        return newItemsList.close
      });
      itemList.nombre = busqueda;
      itemList.getItems();
    },
    cerrar: function() {
        newItemsList.close = true;
      $(`#${newItemsList.IdModal}`).modal('hide');
      newItemsList.close = false;
    }
  }

</script>