$(document).ready(function(){

    $(".td__estado").change((e) =>  {
        const element = $(e.target);

        let val = element.val();
        let tarea_id = element.attr('data-id');
        if(val == "ACTIVO"){
            element.removeClass("td_estado_inact");
            element.addClass("td_estado_act");
        }else
        {
            element.removeClass("td_estado_act");
            element.addClass("td_estado_inact");
        }
        tpIndex.changeStatus(val,tarea_id);
        element.blur();
    })

})

var tpIndex =   {
    endpoint : `${base}api/tareas-programadas`,
    changeStatus :   (estado,tarea_id) =>  {
        GlobalSpinner.mostrar();
        

        $.ajax({
            url :  `${tpIndex.endpoint}/change-status`,
            type : "POST",
            data : {
                estado : estado,
                tarea_id : tarea_id
            },
            success : function(data){
                console.log(data);
                if(data.success){
                    alert("Estado cambiado satisfactoriamente");
                    window.location.reload();
                }
            },
            error : function(xhr, ajaxOptions, thrownError){
                console.log(xhr);
                console.log(thrownError);
                alert("Ha habido un error intente nuevamente");
            },
            complete : function(data){
                GlobalSpinner.ocultar();
            }
        })
    }
}