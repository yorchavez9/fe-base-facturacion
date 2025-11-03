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
        body {
            background-image: url('<?= $this->Url->image($ruta_fondo); ?>');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .container { margin-top: 5vh;}

        .card {
            border-radius: 1rem;
            color: <?= isset($global_brand_data['login_color_txt']) ? $global_brand_data['login_color_txt'] : "#000000" ?>;
        }

        .ingresar {
            color: <?= isset($global_brand_data['login_color_txt']) ? $global_brand_data['login_color_txt'] : "#2c75ff" ?>;
            border-color: <?= isset($global_brand_data['login_color_txt']) ? $global_brand_data['login_color_txt'] : "#2c75ff" ?>;
        }

        a {
            color: <?= isset($global_brand_data['login_color_txt']) && $global_brand_data['login_color_txt'] != "" ? $global_brand_data['login_color_txt'] : "#2c75ff" ?>;
        }

        div.creditos, div.recuperar-clave{
            text-align: center;
            font-size: 0.75rem;
        }
        .div-pwd-input{
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        }
        .pwd-input{
            border: 0;
            outline: none;
            height: 38px;
            color: #495057;
            padding: 6px 12px;
            border-radius: 5px;
        }
        .div-pwd-input:focus-within {
            border-color: #80bdff;
            outline: 0;
            -webkit-box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
            <div class="col-md-12 text-center ">
                <?php echo $this->Flash->render(); ?>
            </div>
            <div class=" col-lg-4 col-md-6 mx-auto" >
                <div class="card shadow" style="background-color: <?= isset($global_brand_data['login_fondo']) ? $global_brand_data['login_fondo'] : "#FFFFFF" ?>;">
                    <div class="card-body" >

                        <?= $this->Form->create(null);?>
                        <?php
                        $logo_ruta = "/" . isset($global_brand_data['global_logo']) ? $global_brand_data['global_logo'] : "";
                        $logo_por_defecto = true;
                        if (!(file_exists($global_brand_data['global_logo']))) {
                            $logo_ruta = "/media/default_logo.png";
                            $logo_por_defecto = false;
                        }
                        echo $this->Html->Image($logo_ruta, ['class'=> 'img img-fluid mb-3','pathPrefix' => '']);
                        ?>
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Usuario / Correo" name="usuario" id="usuarioLogin" type="text" autofocus autocomplete="off" value="<?= $usuario?>">
                            </div>
                            <div class="form-group ">
                                <div class="d-flex align-items-center div-pwd-input">
                                    <input class="col pwd-input" placeholder="Clave" name="clave" id="claveLogin" type="password">
                                    <div class="px-2" style="cursor:pointer" id="div_show_pwd" data-status="false" onclick="showPwd()">
                                    <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <button type="submit" class="btn btn-outline-primary w-100 ingresar" id="btnLogin">
                                <i class="fa fa-sign-in-alt fa-fw" ></i> Ingresar
                            </button>
                            <br/>
                            <br/>
                            <div class="text-center recuperar-clave">
                                <a href="javascript:void(0);" onclick="copyUsuarioToDialog()" data-toggle="modal" data-target="#recuperarClave">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </fieldset>
                        <?= $this->Form->end() ?>

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
                        <input type="text" class="form-control" placeholder="Usuario / Correo" name="usuario2" autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-sm btn-primary" id="recuperarClaveButton">
                                <i class="fas fa-paper-plane fa-fw"></i>
                                Reestablecer
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




