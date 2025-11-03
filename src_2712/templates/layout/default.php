<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $view_title ?> |  <?= $datos_empresa['razon_social'] . " - " ?? '' ?>Sistema de Cotizaciones</title>
    <meta name="description" content="Sistema de Facturación Electrónica y Control de Inventarios">
    <meta name="author" content="Gerware">
    <?php

    $global_brand_data['favicon'] = isset($global_brand_data['favicon']) ? $global_brand_data['favicon'] : "";
    if (!is_file($global_brand_data['favicon'])) {
        $favicon = "/media/default_favicon.png";
    }else{
        $favicon = '/'.$global_brand_data['favicon'];
    }

    echo $this->Html->meta(
        '',
        $favicon,
        ['type' => 'icon']
    );
    echo $this->Html->css("https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i");
    echo $this->Html->css("/assets/font-awesome/css/all.min.css");
    echo $this->Html->css("/assets/bootstrap/css/bootstrap.css");
    echo $this->Html->css("/assets/data-tables/datatables.min.css");
    echo $this->Html->css("/assets/data-tables/dataTables.bootstrap4.min.css");
    echo $this->Html->css("/assets/gijgo/gijgo.css");
    echo $this->Html->css("/assets/colorpicker/css/colorpicker.css");
    echo $this->Html->css("/assets/gerware/gerware.css");
    echo $this->Html->css("/assets/richtext/richtext.min.css");
    echo $this->Html->css("/assets/printjs/print.min.css");
    echo $this->Html->css("/assets/select2/css/select2.min.css");
    // echo $this->Html->css("https://unpkg.com/element-ui/lib/theme-chalk/index.css");
    echo $this->Html->css("/assets/theme-chalk/index.min.css");
    if (isset($styles)) {
        echo "<style type='text/css'>{$styles}</style>";
    }
    echo $this->Html->ScriptBlock("var base = '" . $this->Url->Build("/intranet/", ['fullBase' => true]) . "'");
    echo $this->Html->ScriptBlock("var base_root = '" . $this->Url->Build("/", ['fullBase' => true]) . "'");
    echo $this->Html->ScriptBlock("var API_FE = " . ($flag_dev ? "'http://localhost/fe-api'" : "'https://demo.profecode.com/fe-api'" ) );

    // echo $this->Html->Script("https://cdn.jsdelivr.net/npm/vue@2");
    echo $this->Html->Script("/assets/vue/vue.min.js");
    // echo $this->Html->Script("https://unpkg.com/axios@1.0.0/dist/axios.min.js");
    echo $this->Html->Script("/assets/axios/dist/axios.min.js");

    // obtenemos la imagen de fondo
    $ruta_fondo = '/'. $global_brand_data['global_fondo'];
    if (!file_exists($global_brand_data['global_fondo'])) {
        $ruta_fondo =  '/media/default_fondo.png';
    }

    ?>
    <style type="text/css">
        body {
            background-image: url('<?= $this->Url->image($ruta_fondo); ?>');
        }
        #sidebar ul a,
        #sidebar .gw-userinfo{ color: <?= $global_brand_data['global_color_txt2'] ?> !important;}
        #sidebar .gw-userinfo{border-color: <?= $global_brand_data['global_color_txt2'] ?> !important;}
        .gw-navtop,
        .gw-footer {background-color: <?= $global_brand_data['global_color_bg1'] ?> !important; }
        .gw-navtop a,
        .gw-footer a{ color: <?= $global_brand_data['global_color_txt1'] ?> !important; }
        .gw-navtop .nav-link { color: <?= $global_brand_data['global_color_txt1'] ?> !important; }

        @media (max-width: 900px) {
            .gw-contenedor-contenido{
                padding: 2em;
            }
        }
    </style>
</head>
<body>
<div class="d-flex" id="wrapper" >
    <div class="bg-primary " id="sidebar-wrapper" style="background-color: <?= $global_brand_data['global_color_bg2'] ?> !important;">
        <nav id="sidebar" >
            <div class="sidebar-heading text-center p-2">
                <span class="navbar-brand">
                    <?php
                    $global_brand_data['global_logo'] = isset($global_brand_data['global_logo']) ? $global_brand_data['global_logo'] : "";
                    $logo_ruta = "/" . $global_brand_data['global_logo'];
                    $logo_por_defecto = true;
                    if (!file_exists( $global_brand_data['global_logo'] )){
                        $logo_ruta = "/media/default_logo.png";
                        $logo_por_defecto = false;
                    }
                    echo $this->Html->Image($logo_ruta, ['id' => 'LogoImagen', 'class'=> 'img img-fluid' ,'style' => 'max-height:80px; max-width:200px;']);
                    ?>
                </span>
            </div>
            <div class="gw-userinfo">
                <div class="row">
                    <div class="col-md-12">
                        <?= $usuario_sesion['nombre'];?> <small><b> (<?= $usuario_sesion['rol'] ?>)</b> </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-10" style="font-size: 11px; max-width:190px">
                        <div class="text-truncate" title="<?= $datos_empresa['razon_social'] ?? '' ?>">
                            <i class="fas fa-briefcase"></i> <?= $datos_empresa['razon_social'] ?? '' ?>
                        </div>
                        <div>
                        <i class="fas fa-id-card"></i> RUC: <?= $datos_empresa['ruc'] ?? '' ?>
                        </div>
                    </div>
                    <div class="col-1 text-right">
                        <small>
                            <?= $this->Html->Link("<i class='fas fa-sign-out-alt fa-fw'></i>",['controller' => 'Usuarios', 'action' =>  'cerrarSesion'], ['style'=>'color:#59CB2F;','escape' => false]);?>
                        </small>
                    </div>
                </div>
            </div>
            <?php

                if ($usuario_sesion):
                    echo $this->Element("navleft");
                endif;
            ?>
        </nav>
    </div>
    <div id="page-content-wrapper">
        <?php
        echo $this->Element("navtop");
        echo "<div class='gw-contenedor'>";
        echo $this->Flash->render();
        echo "<div class='gw-contenedor-contenido'>";
        echo $this->fetch("content");
        echo "</div>";
        echo "</div>";
        echo $this->Element("global_spinner");
        echo $this->Element("navbottom");
        if(isset($aviso_mensaje)){
            echo $this->Element("modal_aviso",['mensaje_aviso' => $aviso_mensaje]);
        }
        ?>
        <!-- Modal de prueba de aviso-->
    </div>
</div>
<?= $this->Element('form_soporte_doc') ?>
<?php
echo $this->Html->Script("/assets/jquery/jquery.js");
echo $this->Html->Script("/assets/popper.js");
echo $this->Html->Script("/assets/bootstrap/js/bootstrap.bundle.min.js");
echo $this->Html->Script("/assets/jquery.autocomplete.min.js");
echo $this->Html->Script("/assets/jquery.mask.min.js");
echo $this->Html->Script("/assets/data-tables/datatables.min.js");
echo $this->Html->Script("/assets/gijgo/gijgo.min.js");          // hammer
echo $this->Html->Script("/assets/gijgo/gijgo.messages.es-es.min.js");          // hammer
echo $this->Html->Script("/assets/colorpicker/js/colorpicker.js");
echo $this->Html->Script("/assets/richtext/jquery.richtext.js");
echo $this->Html->Script("/assets/gerware/gerware.js");
echo $this->Html->Script("/assets/chartjs/dist/chart.js");
echo $this->Html->Script("/assets/chartjs/dist/chart.min.js");
echo $this->Html->Script("/assets/printjs/print.min.js");
echo $this->Html->Script("/assets/select2/js/select2.min.js");
echo $this->Html->Script("/assets/plugins/waypoints/lib/noframework.waypoints.min.js");

// echo $this->Html->Script("https://maps.googleapis.com/maps/api/js?key=AIzaSyAAWvXnXoGfrbW_W3muHTmh3C3T1M88uP4");
// compatibilidad con VUE y AXIOS

if (isset($script)) {
    echo $this->Html->ScriptBlock($script);
    echo $this->Html->scriptBlock(" var _csrfToken = '" . $this->request->getParam('_csrfToken') . "';" );
}

// echo $this->Html->Script("https://cdn.jsdelivr.net/npm/vue");

?>
<script type="text/javascript">
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $('#modalAviso').modal('show');
</script>
<?php
echo $this->Element("global_alerta");
?>
</body>
</html>
