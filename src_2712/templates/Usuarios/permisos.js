var permisos_lista = [];
var permisos_lista_check = [];
var permisos_check = [];

$(document).ready(function(){
    getPermisosPerfil();
    $("#filtroPermisos").on("keyup", function () {
        var ttt = $("#filtroPermisos").val();
        if (ttt == '') {
            $(".filtrar").css({"display": "block"})
        } else {
            $(".filtrar").css({"display": "none"})
            $(`.filtrar:containsNC(${ttt})`).css("display", "block");
        }
    })

    $("#filtroPermisosAsignados").on("keyup", function () {
        var ttt = $("#filtroPermisosAsignados").val();
        if (ttt == '') {
            $(".filtrarAsignados").css({"display": "block"})
        } else {
            $(".filtrarAsignados").css({"display": "none"})
            $(`.filtrarAsignados:containsNC(${ttt})`).css("display", "block");
        }
    })



    // console.log($('input[name=permisos]').val());
    $("#filtroPerfiles").change(function(){
        getPermisosPerfil();
    })
    $("#check_todos").click(function(){
        marcarPermisosPefil()
    })
    $("#uncheck_todos").click(function(){
        desmarcarPermisosPerfil()
    })
    $("#drop_todos").click(function(){
        desmarcarPermisosAsignados()
    })
    permisos_check = permisos_check_usuario.map((e) => {
        return e
    })
});


//No se usa
function filtrarPermisosPerfil(){
    GlobalSpinner.mostrar();
    var perfil_id = $('#filtroPerfiles').val();
    var endpoint = `${base}usuarios/get-permisos-perfil/${perfil_id}`;

    if (perfil_id == '1'){
        $("#UsuarioPermisosList").hide();
    }else{
        $("#UsuarioPermisosList").show();
    }

    $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function(data){
            if(data.success){
                console.log( data );
                $('#formUsuariosAddPerfil input[type=checkbox]')
                    .each(
                        function()
                        {
                            // console.log($(this).val());
                            if(data.data.find(permiso => permiso == $(this).val())){
                                console.log(data.data)
                                // $(this).prop('checked',true);
                            }else{
                                // console.log($(this).val())
                                // $(this).prop('checked',false);
                            }
                        }
                    )
            }
            GlobalSpinner.ocultar();
        },
        error : function(xhr, ajaxOptions,thrownError){
            alert(xhr.status);
            alert(thrownError);
            GlobalSpinner.ocultar();
        }
    })

}

function getPermisosPerfil(){
    GlobalSpinner.mostrar();
    var perfil_id = $('#filtroPerfiles').val();
    var endpoint = `${base}usuarios/get-permisos-perfil/${perfil_id}`;

    if (perfil_id == '1'){
        $("#UsuarioPermisosList").hide();
        $("#label_aux_perfil").show('');
    }else{
        $("#UsuarioPermisosList").show();
        $("#label_aux_perfil").hide('');
    }

    $.ajax({
        url: endpoint,
        data: '',
        type: 'GET',
        success: function(data){
            console.log(data)
            if(data.success){
                permisos_lista_check = data.data.map( (e) => {
                    return e
                })
            }
            GlobalSpinner.ocultar();
        },
        error : function(xhr, ajaxOptions,thrownError){
            alert(xhr.status);
            alert(thrownError);
            GlobalSpinner.ocultar();
        },
        complete : function(){
            actualizarListaCheckbox()
        }
    })

}

function actualizarListaCheckbox () {
    var html = '';
    permisos_generales.forEach( e => {

        // if(permisos_lista.find( permiso => permiso == e.codigo || permiso == '*' )){

        // }
        html += `
            <li>
                <label class="filtrar">
                    <input type="checkbox" class="class_permisos_perfil name="permisosCheck[]"
                    value="${e.codigo}" ${ permisos_lista_check.find( check => check == e.codigo) ? 'checked' : '' } onchange="verificarPerfiles(this)" />
                    ${e.nombre}
                </label>
            </li>
        `;
    });
    $("#UsuarioPermisosList ul").html(html)
    actualizarListaUsuario();
}

function actualizarListaUsuario () {
    var html = '';
    permisos_generales.forEach( e => {
        if(permisos_check.find( permiso => permiso == e.codigo )){
            html += `
                <li>
                    <label class="filtrarAsignados">
                        <input type="checkbox" class="class_permisos_asignados" name="permisos[]" value="${e.codigo}" checked onchange="verificarAsignados(this)" />
                        ${e.nombre}
                    </label>
                </li>
            `;

        }
    });
    $("#UsuarioPermisosAsignadosList ul").html( html );
}

function verificarAsignados ( elemet ) {
    var exist = permisos_check.indexOf( $(elemet).val() )
    if( exist != -1 ){
        permisos_check.splice( exist, 1 )
        actualizarListaCheckbox();
    }
}

function verificarPerfiles ( elemet ) {
    if( $(elemet).prop('checked') ){
        permisos_lista_check.push( $(elemet).val() )
    }else{
        var exist = permisos_lista_check.indexOf( $(elemet).val() )
        if( exist != -1 ){
            permisos_lista_check.splice( exist, 1 )
        }
    }
    actualizarListaCheckbox();
}

// function verificarPerfiles ( elemet ) {
//     if( $(elemet).prop('checked') ){
//         permisos_check.push( $(elemet).val() )
//     }else{
//         var exist = permisos_check.indexOf( $(elemet).val() )
//         if( exist != -1 ){
//             permisos_check.splice( exist, 1 )
//         }
//     }
//     actualizarListaCheckbox();
// }

function marcarPermisosPefil () {
    try {
        permisos_lista_check = [];
       $('.class_permisos_perfil')
        .each(
            function()
            {
                permisos_lista_check.push( $(this).val() )
            }
        )
    } catch (error) {
        permisos_lista_check = []
    }
    actualizarListaCheckbox();
}

function desmarcarPermisosPerfil () {
    permisos_lista_check = [];
    actualizarListaCheckbox();
}

function desmarcarPermisosAsignados () {
    try {
        permisos_check = [];
    } catch (error) {
        permisos_check = []
    }
    console.log(permisos_check)
    actualizarListaCheckbox();
}

function asignarPermisosUsuario () {
    try {
        permisos_check = [];
        permisos_lista_check.forEach( e => {
            permisos_check.push( e )
        });
    } catch (error) {
        permisos_check = []
    }
    actualizarListaCheckbox();
}

function aumentarPermisosUsuario () {
    try {
        // permisos_check = [];
        permisos_lista_check.forEach( e => {
            if ( !permisos_check.find( f => f == e ) ) {
                permisos_check.push( e )
            }
        });
    } catch (error) {
        permisos_check = []
    }
    actualizarListaCheckbox();
}

