<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SunatFeNota $sunatFeNota
 */
?>


<div class="container">
<p>
<div id="div_links">
</div>
</p>
    <div class="row">
        <div class="col-12 col-sm-12">
            <table class="table table-bordered table-sm" id="tabla_notas">
                <tr class="text-center">
                    <th colspan="13"></th>
                </tr>
                    <tr>
                        <th><?php // __('Tipo Documento') ?></th>
                        <td colspan="4"><?php // h($sunatFeNota->tipo_doc) ?></td>

                        <th colspan="2">Fecha y Hora </th>
                        <td colspan="6"><?php // $sunatFeNota->fecha_emision->format('Y-m-d H:i:s') ?></td>
                        
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <th >Doc. Afectado</th>
                        <td  colspan="4"><?php // h($sunatFeNota->num_doc_afectado) ?></td>

                        <th colspan="2"><?php // __('Mto Oper Grav.') ?></th>
                        <td colspan="2"><?php // $this->Number->format($sunatFeNota->mto_oper_gravadas) ?></td>
                        <th colspan="2"><?php // __('Mto Igv') ?></th>
                        <td colspan="2"><?php // $this->Number->format($sunatFeNota->mto_igv) ?></td>

                       

                    </tr>
                <tr>
                    <th colspan="5" class="text-center">Datos del Emisor</th>
                    <th colspan="2"><?php // __('Total Imp.') ?></th>
                    <td colspan="2"><?php // $this->Number->format($sunatFeNota->total_impuestos) ?></td>
                    <th colspan="2"><?php // __('Total') ?></th>
                    <td colspan="2"><?php // $this->Number->format($sunatFeNota->mto_imp_venta) ?></td>
                </tr>
                <tr>
                        <th>Ruc </th>
                        <td  ><?php // $sunatFeNota->emisor_ruc ?></td>
                        <th colspan="2">Razón Social</th>
                        <td><?php //$sunatFeNota->emisor_razon_social?></td>
                        <th colspan="4"> Estado del documento </th>
                         <td class="text-center" colspan="4" ><?php // $sunatFeNota->sunat_estado?></td>

                </tr>
                <tr>
                    <th colspan="5" class="text-center">Datos del Receptor</th>
                    <th class="text-center" colspan="8">Motivo</th>
                </tr>
                <tr>
                        <th>Ruc </th>
                        <td  ><?php // $sunatFeNota->cliente_doc_numero ?></td>
                        <th colspan="2">Razón Social</th>
                        <td><?php //$sunatFeNota->cliente_razon_social?></td>
                        <td colspan="8" ><?php // h($sunatFeNota->descripcion_motivo) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12 col-sm-12">
            <h4>Items </h4>
        <br>
            <table class="table table-hover" >
                <thead>
                    <tr>
                        <th>Nombre   </th>
                        <th>Item Unidad   </th>
                        <th>Cantidad   </th>
                        <th>Precios</th>
                    </tr>
                </thead>
                <tbody id="tabla_items">

                
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo $this->Html->ScriptBlock("var nota_id = '{$nota_id}'");
