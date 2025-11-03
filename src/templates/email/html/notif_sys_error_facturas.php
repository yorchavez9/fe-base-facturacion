<div   style="border: 2px solid #E1E6EA;padding: 10px 15px 10px 15px;max-width: 600px;justify-content: center"  >
    <p>
        Ocurrio un error en el host: <a href="<?= $cliente_url ?>" target="_blank"><?= $cliente_url ?></a>
        <br>
        <label style="display: inline-block;max-width:80px;width: 80px"> 
            <b>ID</b>
        </label>
        : # <?= $factura_obj->id ?>
        <br/>
        <label style="display: inline-block;max-width:80px;width: 80px"> 
            <b>Tipo Doc.</b>
        </label>
        : <?= $factura_obj->tipo_doc ?>
        <br/>
        <label style="display: inline-block;max-width:80px;width: 80px"> 
            <b>Serie</b>
        </label>
        : <?= $factura_obj->serie ?>
        <br/>
        <label style="display: inline-block;max-width:80px;width: 80px"> 
            <b>Correlativo</b>
        </label>
        : <?= $factura_obj->correlativo ?>
        <br>
    </p>
    <p>
        <label style="display: inline-block;max-width:80px;width: 80px"> <b>Emisor</b> </label>
        <div>
            <?= $factura_obj->emisor_ruc  ?> - <?= $factura_obj->emisor_razon_social  ?>
        </div>
        <label style="display: inline-block;max-width:80px;width: 80px"> <b>Fechas</b> </label>
        <div>
            F. Emisión: <?= $factura_obj->fecha_emision  ?> 
        </div>
    </p>
    <p>
        <label style="display: inline-block;max-width:80px;width: 80px"> <b>Errores</b> </label>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Err Code</b>
            </label>
            : <?= $factura_obj->sunat_err_code ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Err Message</b>
            </label>
            : <?= $factura_obj->sunat_err_message ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Cdr Notes</b>
            </label>
            : <?= $factura_obj->sunat_cdr_notes ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Cdr Description</b>
            </label>
            : <?= $factura_obj->sunat_cdr_description ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Cdr Code</b>
            </label>
            : <?= $factura_obj->sunat_cdr_code ?>
        </div>
    </p>
    <p>
        Controller->function(): <?= $ubicacion?>
    </p>
</div>
