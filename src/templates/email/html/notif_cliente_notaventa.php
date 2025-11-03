<div   style="border: 2px solid #E1E6EA;padding: 10px 15px 10px 15px;max-width: 600px;justify-content: center"  >
    <div>
        <img style=" max-height: 70px;margin-left: auto;margin-right: auto" src="cid:logo" alt="logo"  />
    </div>
    <p>
        Estimados Srs. <?= $cpe->cliente_razon_social?> <?= $cpe->cliente_doc_numero ?>, <br/>
        le informamos que su Venta ha sido generado correctamente.
    </p>
    <hr color="E1E6EA">
    <p>
        <label style="display: inline-block;width: 150px !important">Cliente</label>
        :  <?=$cpe->cliente_doc_numero ?> - <?=$cpe->cliente_razon_social?>
        <br>
        <label style="display: inline-block;width: 150px !important" >Fecha de emisión</label>
        : <?=$cpe->fecha_venta->format( 'Y-m-d G:i:s')?>
        <br>
        <label style="display: inline-block;width: 150px !important">Nro de Venta</label>
        : <b><?=$cpe->documento_serie?>-<?=$cpe->documento_correlativo?></b>
        <br>
        <label style="display: inline-block;width: 150px !important">Importe</label>
        : <?=$this->Number->format($cpe->total,['places'=>2])?> <?=$cpe->tipo_moneda == 'PEN' ? 'S/' : '$'?>
        <br>
        <label style="display: inline-block;width: 160px !important"></label>
        <small style="color: #9E9E9E">
            <b><?=$cpe->tipo_moneda?></b>
        </small>
        <br>
    </p>
    <p style="color: #9E9E9E;font-size: 0.90rem">

        <span style="color: #9E9E9E">
            <small>
                 Mensaje automático, por favor no responder.
            </small>
        </span>
    </p>




</div>
<div>
    <p>
        Aviso de Confidencialidad:
        <br>
        <br>
        Este correo electrónico y/o el material adjunto es para uso exclusivo de la persona o entidad a la que expresamente se le ha enviado, y puede contener información
        confidencial o material privilegiado. Si usted no es el destinatario legítimo del mismo, por favor repórtelo inmediatamente al remitente del correo y bórrelo.
        Cualquier revisión, retransmisión, difusión o cualquier otro uso de este correo, por personas o entidades distintas a las del destinatario legítimo, queda expresamente prohibido.
        Este correo electrónico no pretende ni debe ser considerado como constitutivo de ninguna relación legal, contractual o de otra índole similar, en consecuencia, no genera obligación alguna a cargo de su emisor o su representada.
        En tal sentido, nada de lo señalado en esta comunicación o en sus anexos podrá ser interpretado como una recomendación sobre los riesgos o ventajas económicas, legales, contables o tributarias, o sobre las consecuencias de realizar o no determinada transacción.
        <br>
        <br>
        Notice of Confidentiality:
        <br>
        <br>
        The information transmitted is intended only for the person or entity to which it is addressed and may contain confidential and/or privileged material.
        Any review, re-transmission, dissemination or other use of, or taking of any action in reliance upon, this information by persons or entities other than the intended recipient is prohibited.
        If you received this in error, please contact the sender immediately by return electronic transmission and then immediately delete this transmission, including all attachments, without copying, distributing or disclosing same, therefore it does not generate any obligation by the issuer or his repesented one.
        In that sense, nothing stated in this communication or its Annexes shall be construed as a recommendation on the risks or economic, legal, accounting or tributary advantages, or on the consequences of performing or not a particular transaction.

    </p>
</div>

