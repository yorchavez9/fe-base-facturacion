$(document).ready(function () {

    $("#btnFullBack").click(function(e){
        e.preventDefault();
        history.back();
    });


    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    (function () {
        jQuery.expr[':'].containsNC = function (elem, index, match) {
            return (elem.textContent || elem.innerText || jQuery(elem).text() || '').toLowerCase().indexOf((match[3] || '').toLowerCase()) >= 0;
        }
    }(jQuery));


    $("#mainnav-toggle").click(function () {
        $("#navbar-header").toggleClass("logo-hide");
        $("#mainnav-container").toggleClass("header-hide");
        $("#navbar-content").toggleClass("side-hide");
        $("#content-container").toggleClass("padding-left");
        $("#footer").toggleClass("padding-left");
    });
});

function copiarCampo(desde, para){
    var valor = $("[name=" + desde + "]").val();
    $("[name=" + para + "]").val(valor);
}


function setImagenParaAdjuntar(element){
    var image = $(element);
    var fileuploader = $(element).next();
    fileuploader.click();
    fileuploader.on("change", function(){
        var preview = image;
        var file    = fileuploader.prop('files')[0];
        var reader  = new FileReader();

        reader.onloadend = function () {
            $(preview).attr("src", reader.result);
            // alert(reader.result);
            //$(preview).attr("src",reader.result);
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    });
}
