<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Gerware/Brandeable">
    <title>Inicio de Sesión | Gerware - Sistema de Cotizaciones</title>
    <!-- Bootstrap Core CSS -->
    <?php
    echo $this->Html->meta(
        'favicon.png',
        '/favicon.png',
        ['type' => 'icon']
    );
    echo $this->Html->css("/assets/bootstrap/css/bootstrap.css");
    echo $this->Html->css("/assets/font-awesome/css/all.min.css");
    echo $this->Html->ScriptBlock("var base = '" . $this->Url->Build("/intranet/", ['fullBase' => true]) . "'");
    $ruta_fondo = '/'. $global_brand_data['global_fondo'];
    if (!file_exists($global_brand_data['global_fondo'])) {
        $ruta_fondo =  '/media/default_fondo.png';
    }

    ?>
    <style type="text/css">
        :root {
            --primary-color: #3F37C9;
            --primary-hover: #3228a8;
            --primary-active: #2b2290;
        }

        body {
            background-image: url('<?= $this->Url->image($ruta_fondo); ?>');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            margin-top: 0;
            padding: 2rem 1rem;
        }

        .login-wrapper {
            max-width: 380px;
            margin: 0 auto;
        }

        .card {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            background: <?= isset($global_brand_data['login_fondo']) ? $global_brand_data['login_fondo'] : "rgba(255, 255, 255, 0.95)" ?>;
            color: <?= isset($global_brand_data['login_color_txt']) ? $global_brand_data['login_color_txt'] : "#2c3e50" ?>;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
        }

        .card-body {
            padding: 3rem 2.5rem;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1rem;
        }

        .logo-container img {
            max-height: 80px;
            max-width: 100%;
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 0.75rem;
            border: 2px solid #e0e6ed;
            padding: 0.875rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            height: 48px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(63, 55, 201, 0.15);
            background: #fff;
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        .div-pwd-input {
            position: relative;
            border: 2px solid #e0e6ed;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            height: 48px;
        }

        .div-pwd-input:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(63, 55, 201, 0.15);
            background: #fff;
            transform: translateY(-2px);
        }

        .pwd-input {
            border: 0;
            outline: none;
            padding: 0.875rem 1.25rem;
            font-size: 1rem;
            background: transparent;
            color: #2d3748;
            flex: 1;
            height: 100%;
        }

        .pwd-toggle {
            padding: 0 1rem;
            cursor: pointer;
            color: #718096;
            transition: color 0.3s ease;
            user-select: none;
        }

        .pwd-toggle:hover {
            color: var(--primary-color);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(63, 55, 201, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary-active) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(63, 55, 201, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(63, 55, 201, 0.3);
        }

        .btn-login:focus {
            box-shadow: 0 0 0 0.25rem rgba(63, 55, 201, 0.5);
            color: white;
        }

        .recuperar-clave {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .recuperar-clave a {
            color: <?= isset($global_brand_data['login_color_txt']) && $global_brand_data['login_color_txt'] != "" ? $global_brand_data['login_color_txt'] : "var(--primary-color)" ?>;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .recuperar-clave a:hover {
            color: var(--primary-hover);
            transform: translateX(3px);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            border-radius: 1rem 1rem 0 0;
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-header .modal-title {
            font-weight: 600;
        }

        .modal-header .close {
            color: white;
            opacity: 0.9;
            text-shadow: none;
        }

        .modal-header .close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 2rem;
        }

        .input-group-text {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .input-group .form-control {
            border-radius: 0;
        }

        .input-group-append .btn {
            border-radius: 0 0.5rem 0.5rem 0;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease;
        }

        /* Flash messages */
        .alert {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .btn-login {
                font-size: 1rem;
                padding: 0.875rem;
            }
        }
    </style>
    <script type="text/javascript">
        //<![CDATA[
        function copyUsuarioToDialog(){
            var user = $("[name=usuario]").val();
            $("[name=usuario2]").val(user);
        }

        function recuperarClave(){

            var correo = $("input[name='usuario2']").val();

            $("#recuperarClaveButton").html("<i class='fas fa-spin fa-spinner fa-fw'></i> Reiniciando clave ...");
            $("#recuperarClaveButton").attr("disabled", true)
            var endpoint = `${base}usuarios/recuperar-clave/`

            $.ajax({
                url: endpoint,
                data: {
                    usuario2: correo
                },
                type: 'POST',
                success: function(r){
                    alert(r.message)
                    if(r.success){
                        $("#recuperarClaveButton").html("<i class='fas fa-check fa-fw'></i> Clave Reiniciada");
                    }else{
                        $("#recuperarClaveButton").html("<i class='fas fa-paper-plane fa-fw'></i> Reestablecer");
                    }
                    $("#recuperarClaveButton").attr("disabled", false)
                },
                error: function(r){
                    alert("Ocurrio un error, vuelva a intentarlo en unos minutos")
                },
                complete: function(){
                    $("[name=clave]").val('');

                }
            });

            return false;
        }

        //]]>
        function showPwd () {
            var show = document.getElementById("div_show_pwd");
            var status = show.getAttribute('data-status');
            show.setAttribute('data-status' , status == 'true' ? 'false' : 'true' )
            if(status == 'true'){
                document.getElementById("claveLogin").setAttribute('type', 'password');
                show.innerHTML = '<i class="fas fa-eye"></i>'
            }else{
                document.getElementById("claveLogin").setAttribute('type', 'text');
                show.innerHTML = '<i class="fas fa-eye-slash"></i>'
            }
        }

    </script>
</head>
<body>
    <div class="container" >
        <div class="row">
            <div class="col-md-12 text-center mb-3">
                <?php echo $this->Flash->render(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="login-wrapper">
                    <div class="card" style="background-color: <?= isset($global_brand_data['login_fondo']) ? $global_brand_data['login_fondo'] : "rgba(255, 255, 255, 0.95)" ?>;">
                        <div class="card-body">
                            <?= $this->Form->create(null);?>

                    <div class="logo-container">
                        <?php
                        $logo_ruta = "/" . isset($global_brand_data['global_logo']) ? $global_brand_data['global_logo'] : "";
                        $logo_por_defecto = true;
                        if (!(file_exists($global_brand_data['global_logo']))) {
                            $logo_ruta = "/media/default_logo.png";
                            $logo_por_defecto = false;
                        }
                        echo $this->Html->Image($logo_ruta, ['class'=> 'img img-fluid','pathPrefix' => '']);
                        ?>
                    </div>

                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Usuario / Correo" name="usuario" id="usuarioLogin" type="text" autofocus autocomplete="off" value="<?= $usuario?>">
                        </div>
                        <div class="form-group">
                            <div class="div-pwd-input">
                                <input class="pwd-input" placeholder="Clave" name="clave" id="claveLogin" type="password" autocomplete="current-password">
                                <div class="pwd-toggle" id="div_show_pwd" data-status="false" onclick="showPwd()">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login" id="btnLogin">
                            <i class="fa fa-sign-in-alt fa-fw"></i> Ingresar
                        </button>

                        <div class="recuperar-clave">
                            <a href="javascript:void(0);" onclick="copyUsuarioToDialog()" data-toggle="modal" data-target="#recuperarClave">
                                <i class="fas fa-key fa-sm"></i> ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </fieldset>
                            <?= $this->Form->end() ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="recuperarClave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" class="modal-content" onsubmit="return recuperarClave();">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Recupera tu Clave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-user fa-fw"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" placeholder="Usuario / Correo" name="usuario2" autocomplete="off" style="border-radius: 0;">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" id="recuperarClaveButton" style="border-radius: 0 0.5rem 0.5rem 0;">
                                <i class="fas fa-paper-plane fa-fw"></i> Reestablecer
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <?php
        echo $this->Html->Script("/assets/jquery/jquery.js");          // jQuery
        echo $this->Html->Script("/assets/bootstrap/js/bootstrap.bundle.min.js");          // bootstrap
    ?>
    <script>
        $( document ). ready(function() {
            $('#recuperarClave').on('shown.bs.modal', function (event) {
                $("[name=usuario2]").focus();
            })
            $('#recuperarClave').on('hide.bs.modal', function (event) {
                $("[name=usuario2]").val('');
                $("#recuperarClaveButton").attr("disabled", false)
                $("#recuperarClaveButton").html("<i class='fas fa-paper-plane fa-fw'></i> Reestablecer");
            })
        })
    </script>
</body>
</html>




