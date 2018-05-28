$( document ).ready(function() {
    $('.activa_info').on('click',function(){
        if($('#info_'+this.id).css('display') == 'none')
            $('#info_'+this.id).css('display','flex');
        else
            $('#info_'+this.id).css('display','none');
    });
    $('#insertar').on('click',function(){
        $('#accion').val('insertar');
        $('#formulario_tablas').submit();
    });
    $('#actualizar').on('click',function(){
        $('#accion').val('actualizar');
        $('#formulario_tablas').submit();
    });
    $('#eliminar').on('click',function(){
        $('#accion').val('eliminar');
        $('#formulario_tablas').submit();
    });
    $('#traer_tarea').on('click',function(){
        $('#accion').val('traer_tarea');
        $('#formulario_tablas').submit();
    });

    
});

$(document).ready(function(){
    
    var tiempo = {
        hora: 0,
        minuto: $('#n_horas').val(),
        segundo: 0
    };

    var update = null;
    var tiempo_corriendo = null;

    $("#btn-comenzar").click(function(){
        if ( $(this).val() == 'Comenzar' )
        {
            $(this).val('Detener');                        
            tiempo_corriendo = setInterval(function(){
                // Segundos
                tiempo.segundo++;
                if(tiempo.segundo >= 60)
                {
                    tiempo.segundo = 0;
                    tiempo.minuto++;
                }      

                $("#n_horas").val(tiempo.minuto);
            }, 1000);

            update =  setInterval(function(){//Se guarda automaticamente en BD cada minuto
                if($('#id_tarea').val() != '' && $('#n_horas').val() != '' && $('#accion_helper').val() != ''){
                    if($('#accion_helper').val() == 'traer_tarea'){
                        var id_tarea = $('#id_tarea').val();
                        var n_horas = $('#n_horas').val();
                        
                        $.ajax({
                            type: "POST",
                            url: "modulos/update_ajax.php",
                            data: {"id_tarea":id_tarea, "n_horas":n_horas},
                            success: function() {    
                                $('#updated-box').css('display','inherit');                          
                            }
                        });
                    }
                }
            },60000);
        }
        else 
        {
            $(this).val('Comenzar');
            clearInterval(tiempo_corriendo);
            clearInterval(update);
        }
    });
});

$(document).ready(function() {	
    
 
    
});