$(document).ready(function(){

    // seteamos los colores
    $(".color").each(function(i, input){
        var color = $(input).val();
        console.log(color)
        $(input).css({
            "background":  color
        });
    })


    $('.color').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val("#" + hex);
            $(el).css({"background": "#" + hex})
            $(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    })
    .bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    });

    $(".gw_buploader .img").click(function(){



    });


})

