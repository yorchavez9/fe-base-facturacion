<div class="row pt-3">
    <br>
    <br>
    <div class="col-md-12 text-center">
        <a class="btn btn-sm btn-primary" target="_blank" href="<?=$this->Url->Build(['action'=>'descargarPdf', $coti_id, 'a4','0'])?>"> <i class="fa fa-print fa-fw"></i>  Imprimir A4 </a>
        <a class="btn btn-sm btn-primary" target="_blank" href="<?=$this->Url->Build(['action'=>'descargarPdf', $coti_id, 'ticket','0'])?>"> <i class="fa fa-print fa-fw"></i>  Imprimir Ticket </a>
        <a class="btn btn-sm btn-primary" href="#" onclick="ModalDescargarEnviar.Abrir('COTIZACION', <?=$coti_id?>)" >
            <i class="fa fa-paper-plane fa-fw"></i> Enviar por
            <i class="fab fa-whatsapp fa-fw"></i> ó <i class="fa fa-envelope fa-fw"></i>
        </a>
        <a class="btn btn-sm btn-primary mt-1 mt-sm-1" href="<?=$this->Url->Build(['action'=>'index'])?>"> <i class="fa fa-arrow-right fa-fw"></i> Continuar</a>

    </div>


</div>


<?php echo $this->Element('modal_descargar_enviar');?>
