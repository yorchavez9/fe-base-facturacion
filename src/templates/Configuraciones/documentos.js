$(document).ready(function () {

    $('[name=cabecera]').richText({
        fontSize: false,
        videoEmbed: false,
        fileUpload: false,
        imageUpload: false
    });

    $('[name=pie]').richText({
        fontSize: false,
        videoEmbed: false,
        fileUpload: false,
        imageUpload: false
    });

    $('#btnLimpiar').on('click', function (e) {
        e.preventDefault();
        $('[name=cabecera]').val('');
        $('[name=pie]').val('');

        $('.richText-editor').html('');
    });

});


