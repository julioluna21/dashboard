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
	
	$("#formrnotas").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardarnotas(e);//guarda o edita el articulo
    });
	
	$("#formrfecha").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardarfechas(e);//guarda o edita el articulo
    });
	
	$.post("../Control/NovedadFlotaControl.php?op=select", function(data)
    {
     $("#vehiculo").html(data);
    });
	
	
	$('#vehiculo').select2({
    width: '100%' ,
    
});
	
$("#operatividad").on('change', function () {
	var operatividad=$("#operatividad").val();
	if(operatividad=="INOPERATIVO"){
	$("#dvcontigngencia").show();	
    $("#dvplaca").show();			
	}else{
	$("#dvcontigngencia").hide();	
    $("#dvplaca").hide();		
	}
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
		    $("#dvcontigngencia").hide();	
            $("#dvplaca").hide();
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
function mostrarformu(flag)
{
    if (flag==1)
    {//partes de la pagina que se muestran o se ocultan
      $('#formregistros').show();  
	  $('#formrnotas').hide();
	  $('#formrfecha').hide();	
	  $('#btnfechas').hide();	
	  $("#fechasdato").show();	
	  $("#fechainicio").attr('required', '');	
	  $("#fechafinal").attr('required', '');		
    }
    else if(flag==2){		
      $('#formrnotas').show();  	
	  $('#formregistros').hide();
	  $('#formrfecha').hide();	
	  $("#btnGuardar2").prop("disabled",false);	
             
    }
	else if(flag==3){	
	  $('#formrfecha').show();		
      $('#formrnotas').hide();  	
	  $('#formregistros').hide();
	  $("#btnGuardar3").prop("disabled",false);	
             
    }
}


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
document.getElementById("formrnotas").reset();
document.getElementById("formrfecha").reset();
	$("#idnovedad").val("");
	$('#vehiculo').val("").trigger('change'); 
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
                                    url: '../Control/NovedadFlotaControl.php?op=listar&estado='+estado,//pagina que realiza la operación
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
            url: "../Control/NovedadFlotaControl.php?op=guardar",
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


function guardarnotas(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar2").prop("disabled",true);
    var formData = new FormData($("#formrnotas")[0]);
    $.ajax({
        url: "../Control/NovedadFlotaControl.php?op=guardarNota",
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
}


function guardarfechas(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar3").prop("disabled",true);
    var formData = new FormData($("#formrfecha")[0]);
    $.ajax({
        url: "../Control/NovedadFlotaControl.php?op=editarFechas",
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
}


function mostrarnotas(id)
{
$("#idnovedad2").val(id);
mostrarform(true);
mostrarformu(2);	
    
}

function mostrarfechas()
{
mostrarform(true);
mostrarformu(3);	
    
}

function mostrarcontenido(idnovedad)
{ 
	$.post("../Control/NovedadFlotaControl.php?op=mostrarNovedad",{idnovedad : idnovedad}, function(data)
    {  
	$("#contenidoNota").html(data)
    
    });
}




function mostrar(idnovedad)
{
    
    $.post("../Control/NovedadFlotaControl.php?op=mostrar",{idnovedad : idnovedad}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
	mostrarformu(1);		 
    mostrarform(true)       
    $("#idnovedad").val(data.ID_NOVEDAD);
	$("#idnovedad3").val(data.ID_NOVEDAD);		 
    $("#fechainicio2").val(data.FECHA_HORA_INICIO); 
	$("#fechafinal2").val(data.FECHA_HORA_FIN); 
    $("#tiponovedad").val(data.TIPO_NOVEDAD); 
	$("#operatividad").val(data.OPERATIVIDAD); 		 
	$("#novedad").val(data.NOVEDAD); 	
	$("#contingencia").val(data.CONTIGENCIA); 		 
	$("#palacacont").val(data.PLACACONTIGENCIA); 
    $("#Sistema").val(data.SISTEMA); 			 
    $('#vehiculo').val(data.ID_VEHICULO_NOVEDAD).trigger('change'); 
    $("#fechasdato").hide();	
	$("#fechainicio").removeAttr('required');	
	$("#fechafinal").removeAttr('required');	
	$('#btnfechas').show();		 
    if(data.OPERATIVIDAD=="INOPERATIVO"){
	$("#dvcontigngencia").show();	
    $("#dvplaca").show();			
	}		 
    }, 1000);      
   
     
    });
    
    
}
function anular(idnovedad){
    
     bootbox.confirm({
            message: 'Desea anular este registro?',
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
                  $.post("../Control/NovedadFlotaControl.php?op=desactivar",{idnovedad : idnovedad}, function(data)
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
function activar(idnovedad){
    
     bootbox.confirm({
            message: "Desea activar este registro?",
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
                  $.post("../Control/NovedadFlotaControl.php?op=activar",{idnovedad : idnovedad}, function(data)
            {
      bootbox.alert({
                        title: 'Activado!',
                        message: data,
                        size: 'small',
                        closeButton: false
                    });      
                      
        setTimeout(() => {
                        bootbox.hideAll()
        }, 1500);              
         tabla.ajax.reload();
        
    });
                }
            }
        });
    
}



init();//ejecuta la función init