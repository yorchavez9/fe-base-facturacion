<div class="modal fade " id="modalItemsOriginalesNotaCredito" tabindex="-1" role="dialog" aria-hidden="=true" aria-labelledby="#labelModalItemsOriginalesNotaCredito">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title modal-title-reporte-error" id="labelItemsOriginalesNotaCredito">
                    Productos <i  class="fa fa-smile fa-fw text-success"></i>
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <table class="table table-responsive-xl">
                            <thead>
                                <tr class="cabecera_detalle">
                                    <th></th>
                                    <th>#</th>
                                    <th>Descripción</th>
                                    <th>Unidad</th>
                                    <th>Afect. IGV</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Sub Total</th>
                                    <th>IGV</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="itemsNotaCredito">
                                <?php
                                if (isset($items) && count($items->toArray()) > 0) {
                                    $i = 0;
                                    foreach ($items as $item) {
                                        $i++;
                                        echo "<tr>";
                                        echo "<td><button type='button' onclick='ModalNotaCreditoModificarItemsOriginales.agregarItem(this)' data-id='$item->id' class='btn btn-sm btn-outline-primary '  > <i class='fa fa-plus fa-fw' ></i> </button></td>";
                                        echo "<td>$i</td>";
                                        echo "<td width='270px'> $item->item_nombre </td>";
                                        echo "<td> $item->item_unidad </td>";
                                        echo "<td width='120px'> " . $array_afectacion_igv[$item->afectacion_igv] . " </td>";
                                        echo "<td > $item->cantidad</td>";
                                        echo "<td width='120px'>" . $this->Number->format($item->precio_uventa, ['places' => 2]) . "</td>";
                                        echo "<td> " . $this->Number->format($item->subtotal , ['places' => 2]) . "</td>";
                                        echo "<td> $item->igv_monto </td>";
                                        echo "<td> $item->precio_total </td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->ScriptBlock("var _items_originales_nota_credito =" . json_encode($items) . ";") ?>
<script>
    var ModalNotaCreditoModificarItemsOriginales = {
        idModal: "modalItemsOriginalesNotaCredito",
        titleModal: "labelItemsOriginalesNotaCredito",
        items: [],
        init: () => {
            ModalNotaCreditoModificarItemsOriginales.items = _items_originales_nota_credito;
        },
        callback: null,
        open: () => {
            $(`#${ModalNotaCreditoModificarItemsOriginales.idModal}`).modal("show");
        },
        close: () => {
            $(`#${ModalNotaCreditoModificarItemsOriginales.idModal}`).modal("hide");
        },
        agregarItem: (element) => {
            const item_id = $(element).attr('data-id');
            const item = ModalNotaCreditoModificarItemsOriginales.items.find((i) => i.id == item_id);

            if (item) {
                ModalNotaCreditoModificarItemsOriginales.callback(item);
            }
        }




    }
</script>