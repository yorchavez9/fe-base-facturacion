$(document).ready(function(){
    $("#btnNuevo").click(function(e){
        e.preventDefault()
        FormUsuarios.Nuevo();
    })
});

function cambiarClave (usuario_id = '' , usuario = '') {
    CambioClave.Nuevo(usuario_id , usuario );
}