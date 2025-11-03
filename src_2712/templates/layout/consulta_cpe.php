<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $view_title ?> |  Sistema de Cotizaciones v1 by Gerware</title>
    <meta name="description" content="">
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
    echo $this->Html->css("/assets/gerware/gerware.css");
    if (isset($styles)) {
        echo "<style type='text/css'>{$styles}</style>";
    }
    echo $this->Html->ScriptBlock("var base = '" . $this->Url->Build("/intranet/", ['fullBase' => true]) . "'");
    echo $this->Html->ScriptBlock("var base_root = '" . $this->Url->Build("/", ['fullBase' => true]) . "'");


    ?>
    <style type="text/css">

        #sidebar ul a { color: <?= $global_brand_data['global_color_txt2'] ?> !important;}
        .gw-navtop,
        .gw-footer {background-color: <?= $global_brand_data['global_color_bg1'] ?> !important; }
        .gw-navtop a,
        .gw-footer a{ color: <?= $global_brand_data['global_color_txt1'] ?> !important; }
        .gw-navtop .nav-link { color: <?= $global_brand_data['global_color_txt1'] ?> !important; }
    </style>
</head>
<body>
<div class="d-flex" id="wrapper" >

    <div id="page-content-wrapper">
        <?php
        echo $this->fetch("content");
        echo "</div>";
        echo "</div>";
        echo $this->Element("global_spinner");
        ?>
    </div>
</div>
<?php
echo $this->Html->Script("/assets/jquery/jquery.js");
echo $this->Html->Script("/assets/popper.js");
echo $this->Html->Script("/assets/bootstrap/js/bootstrap.bundle.min.js");
echo $this->Html->Script("/assets/jquery.autocomplete.min.js");
echo $this->Html->Script("/assets/jquery.mask.min.js");
echo $this->Html->Script("/assets/gerware/gerware.js");

if (isset($script)) {
    echo $this->Html->ScriptBlock($script);
    echo $this->Html->scriptBlock(" var _csrfToken = '" . $this->request->getParam('_csrfToken') . "';" );
}
?>
<script type="text/javascript">
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
</body>
</html>
