$(document).ready(function () {

    $('[name=pie]').richText({
        fontSize: false,
        videoEmbed: false,
        fileUpload: false,
        imageUpload: false
    });

    $('#btnLimpiar').on('click', function (e) {
        e.preventDefault();
        $('[name=pie]').val('');

        $('.richText-editor').html('');
    });

});


