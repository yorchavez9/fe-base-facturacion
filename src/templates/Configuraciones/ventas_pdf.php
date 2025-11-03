
<div class="container">
    <form method="POST">
        <legend>Configuracion de los PDF de ventas</legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Tipo de Precio por Producto a mostrar en el pdf</label>
                    <?= $this->Form->control("ven_tipo_preciov_pdf", [ 'label' => false , 'class' => 'form-control', 'options' => ['1' => 'Precio Venta', '0' => 'Valor Venta'], 'value' => $vars['ven_tipo_preciov_pdf']])?>
                </div>
            </div>
            <div class="col-md-6 pt-2 px-5">
                <div class="form-group mt-4">
                    <input type="checkbox" name="allow_fotos_coti" id="allow_fotos_coti" class="checkbox" value="1" <?= $vars['allow_fotos_coti'] == '0' || $vars['allow_fotos_coti'] == ''  ? '' : 'checked' ?>/>
                    <label class="control-label" for="allow_fotos_coti">Mostrar Fotos en las proformas</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p  style="opacity: 0.5">
                    <b>NOTA</b> : Esta modificación es para especificar que tipo de precio se va a mostrar por producto
                    en los comprobantes de venta, si en esta opción se especifica valor de venta, en los pdf de los comprobantes
                    de venta, cada producto será específicado son su precio sin igv, y en el caso de seleccionar precio de venta
                    los precios mostrados en el pdf serán con igv
                </p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6 pt-2 px-5">
                <div class="form-group mt-4">
                    <input type="checkbox" name="allow_valcpe_ver" id="allow_valcpe_ver" class="checkbox" value="1" <?= $vars['allow_valcpe_ver'] == '0' || $vars['allow_valcpe_ver'] == ''  ? '' : 'checked' ?>/>
                    <label class="control-label" for="allow_valcpe_ver">Ver links de validez CPE</label>
                </div>
            </div>
        </div>
        
     <!--<div class="row">
            <div class="col-md-3 my-2">
                <label for="">Cant. decimales a manejar en ventas</label>
                <?= $this->Form->control("cant_dec_item", [ 'label' => false , 'type' => 'number', 'step' => '1', 'class' => 'form-control', 'value' => ($vars['cant_dec_item'] == '') ? '2' : $vars['cant_dec_item'] ,  'min' => '1', 'required'])?>
            </div>
        </div>-->


        <div class="row">

            <button type="submit" class="btn btn-sm btn-primary">
                <i class=" fa fa-save fa-fw"></i> Guardar
            </button>
        </div>
    </form>

</div>
