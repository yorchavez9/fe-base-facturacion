<div   style="border: 2px solid #E1E6EA;padding: 10px 15px 10px 15px;max-width: 600px;justify-content: center"  >
    <p>
        Ocurrio un error en el host: <a href="<?= $cliente_url ?>" target="_blank"><?= $cliente_url ?></a>
        <br>
        <label style="display: inline-block;max-width:80px;width: 80px"> 
            <b>ID</b>
        </label>
        : # <?= $res_diario_obj->id ?>
        <br/>
        <label style="display: inline-block;max-width:80px;width: 80px"> 
            <b>Correlativo</b>
        </label>
        : <?= $res_diario_obj->correlativo ?>
        <br>
        <label style="display: inline-block;max-width:80px;width: 80px"> 
            <b>Ticket</b>
        </label>
        : <?= $res_diario_obj->ticket ?>
        <br>
    </p>
    <p>
        <label style="display: inline-block;max-width:80px;width: 80px"> <b>Emisor</b> </label>
        <div>
            <?= $res_diario_obj->emisor_ruc  ?> - <?= $res_diario_obj->emisor_razon_social  ?>
        </div>
        <label style="display: inline-block;max-width:80px;width: 80px"> <b>Fechas</b> </label>
        <div>
            F. Generación: <?= $res_diario_obj->fecha_generacion  ?> 
            F. Resumen: <?= $res_diario_obj->fecha_resumen  ?> 
        </div>
    </p>
    <p>
        <label style="display: inline-block;max-width:80px;width: 80px"> <b>Errores</b> </label>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Err Code</b>
            </label>
            : <?= $res_diario_obj->sunat_err_code ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Err Message</b>
            </label>
            : <?= $res_diario_obj->sunat_err_message ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Cdr Notes</b>
            </label>
            : <?= $res_diario_obj->sunat_cdr_notes ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Cdr Description</b>
            </label>
            : <?= $res_diario_obj->sunat_cdr_description ?>
        </div>
        <div>
            <label style="display: inline-block;max-width:80px;width: 80px"> 
                <b>Sunat Cdr Code</b>
            </label>
            : <?= $res_diario_obj->sunat_cdr_code ?>
        </div>
    </p>
    <p>
        Controller->function(): <?= $ubicacion?>
    </p>
</div>
