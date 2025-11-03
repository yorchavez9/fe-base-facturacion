<div   style="border: 2px solid #E1E6EA;padding: 10px 15px 10px 15px;max-width: 600px;justify-content: center"  >
    <div style="text-align:center">
        <img style=" max-height: 180px;margin-left: auto;margin-right: auto" src="cid:header" alt="header"  />
    </div>
    <p>
        ¡Hola <?=$usuario->nombre?>! esperamos que te encuentres muy bien, el siguiente
        correo es para notificar el cambio de clave:
        <br>
        <br>
        <label style="display: inline-block;max-width:80px;width: 80px"> <b>Usuario</b></label>
        : <?= $usuario->usuario ?>
        <br/>
        <br>
        <label style="display: inline-block;max-width:80px;width: 80px"><b>Clave</b></label>
        : <?= $clave ?><br/>
        <br>
        <?=$this->Html->link("Inicie sesión aquí.",['controller'=>'Usuarios','action'=>'iniciarSesion'],[ 'fullBase' =>true ,'escape'=>false,'style'=> 'color: #9D9D9D;'  ])?>
        <br>
        <br>
    </p>
    <div style="text-align:center">
        <img style=" max-height: 180px;margin-left: auto;margin-right: auto" src="cid:footer" alt="footer"  />
    </div>
</div>
