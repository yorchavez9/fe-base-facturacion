<div   style="border: 2px solid #E1E6EA;padding: 10px 15px 10px 15px;max-width: 600px;justify-content: center"  >
    <div>
        <img style=" max-height: 70px;margin-left: auto;margin-right: auto" src="cid:logo" alt="logo"  />
    </div>
    <?php if (isset($toUsuario) && $toUsuario) : ?>
        <p>
            ¡Hola <?=$usuario->nombre?>! esperamos que te encuentres muy bien, el siguiente
            correo es para notificar tu clave de acceso al sistema:
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
    <?php else: ?>
        <p>
            Se registro el siguiente usuario para <?=$usuario->nombre?>:
            <br>
            <br>
            <label style="display: inline-block;max-width:80px;width: 80px"> <b>Usuario</b></label>
            : <?= $usuario->usuario ?>
            <br/>
            <br>
            <label style="display: inline-block;max-width:80px;width: 80px"><b>Clave</b></label>
            : <?= $clave ?><br/>
            <br>
            <br>
        </p>
    <?php endif; ?>
</div>
