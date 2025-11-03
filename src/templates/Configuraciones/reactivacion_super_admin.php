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

    </style>
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
                            <h5 class="text-center pb-3">
                                Reactivacion del Sistema
                            </h5>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Usuario" name="user" id="usuarioLogin" type="text" autofocus autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Clave" name="pwd" id="claveLogin" type="password">
                                </div>
                                <div class="form-group">
                                    <select name="activo" id="" class="form-control">
                                        <option value="1" >Activo</option>
                                        <option value="0" >Inactivo</option>
                                    </select>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-outline-primary w-100 ingresar" id="btnLogin">
                                    <i class="fa fa-sign-in-alt fa-fw" ></i> Reactivar Sistema
                                </button>
                                <br/>
                            </fieldset>
                        <?= $this->Form->end() ?>

                        <hr/>
                        <div class="text-center creditos">
                            <a href="https://www.factura24.pe" target="_blank"
                               title="Sistema de facturación electrónica con control de inventarios en Perú">Factura24</a> by
                            <a href="https://www.gerware.com" target="_blank"
                               title="Empresa desarrolladora de Software en Perú">Gerware</a>
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




