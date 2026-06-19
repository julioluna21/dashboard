var tabla;//variable global
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false);
    listar(1);//lista 
   
 
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	
	$("#formdetalle").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardarDetalle(e);//guarda o edita el articulo
    });
	
	$.post("../Control/NovedadesControl.php?op=select", function(data)
    {
     $("#Proyecto").html(data);
    });
	
	$.post("../Control/NovedadesControl.php?op=select2", function(data)
    {
     $("#uen").html(data);
    });
	
	$('#Proyecto').select2({
    width: '100%' ,
    
});

	$('#uen').select2({
    width: '100%' ,
    
});
	
	
 

}


//Función mostrar formulario
function mostrarform(flag)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
            $('#formularioregistros').show();
            $('#listadoregistros').hide();
            $('#btnagregar').hide();
            $("#btnGuardar").prop("disabled",false);
    }
    else
    {		
             $("#btnGuardar").prop("disabled",false);
             $('#formularioregistros').hide();
             $('#btnagregar').show();
             $('#listadoregistros').show();
           
             
    }
}

//Función mostrar formulario



//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false);
}


//Función limpiar, pone el formulario en blanco
function limpiar()
{
document.getElementById("formregistros").reset();
$("#idnovedad").val("");
$('#Proyecto').val("").trigger('change'); 
$('#uen').val("").trigger('change'); 	
}
//Función Listar
function listar(estado)
{
    tabla=$('#tbllistado').dataTable(//Carga variable con datos datatable
    {
            "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla Bfrtip
        buttons: [
                        
                    ],
		 language: {
      search: 'Buscar ',
      paginate: {
        first: 'Primero',
        previous: 'Anterior',
        next: 'Siguiente',
        last: 'Último'
      }},
        "ajax"://metodo ajax
                            {
                                    url: '../Control/NovedadesControl.php?op=listar&estado='+estado,//pagina que realiza la operación
                                    type : "get",//tipo de envio de datos
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[ 2, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
    
}
//Función para guardar o editar
function guardar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formregistros")[0]);
    $.ajax({
            url: "../Control/NovedadesControl.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
            mostrarform(false);
            limpiar();
            tabla.ajax.reload();
            //$(location).attr('href','../Vista/index.html');    
            
            
            
        }
    });
    limpiar();
}


function guardarDetalle(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar2").prop("disabled",true);
    var formData = new FormData($("#formdetalle")[0]);
    $.ajax({
            url: "../Control/NovedadesControl.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            $("#btnGuardar2").prop("disabled",false);
            $("#novedad2").val("");  
            
        }
    });
    var id=$("#idnovedad").val();
	mostrarcontenido(id);
}



function mostrarcontenido(idnovedad)
{ 
	$("#idnovedad").val(idnovedad);
	$("#novedad2").val("");  
	$.post("../Control/NovedadesControl.php?op=mostrarNovedad",{idnovedad : idnovedad}, function(data)
    {  
	$("#contenidoNota").html(data)
    
    });
}


function finalizar(idnovedad){
    
     bootbox.confirm({
            message: 'Desea cerrar esta novedad?',
            buttons: {
                confirm: {
                    label: 'SI'
                },
                cancel: {
                    label: 'NO'
                }
            },
            callback: function (result) {
                if (result) {
                  $.post("../Control/NovedadesControl.php?op=finalizar",{idnovedad : idnovedad}, function(data)
            {
      bootbox.alert({
                        title: 'Desactivado!',
                        message: data,
                        size: 'small',
                        closeButton: false
         });              
        
     tabla.ajax.reload();
        
    });
                }
            }
        });
    
}




init();//ejecuta la función init