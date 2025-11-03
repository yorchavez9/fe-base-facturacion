$(document).ready(function(){


    $('input[name=ubigeo_dpr]').autocomplete({
        minChars        : 0,
        //        paramName       : 'busqueda',
        //        serviceUrl      : _base + 'busqueda/autocomplete/ubicaciones',
        autoSelectFirst : true,
        showNoSuggestionNotice : 'Sin resultados',
        lookup          : ubicaciones,
        onSelect        : function(suggestion) {
            console.log(suggestion);
            $("input[name=ubigeo]").val(suggestion.id);
        },
        onSearchComplete   : function(query, suggestions){
        },
        onHint: function (hint) {
            $('#autocomplete-ajax-x').val(hint);
        },
        onInvalidateSelection: function() {
            $('#selction-ajax').html('You selected: none');
        }
    });

    $(`[name=codigo]`).mask('0000');
    $(`[name=telefono]`).mask('000000000');

});
