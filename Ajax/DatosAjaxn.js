var tabla,estado;//variable global
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false,false);
	estado=1;
    listar();//lista 
 
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	
   $("#formrcsv").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardarCSV(e);//guarda o edita el articulo
    });
		
	 $.post("../Control/DatosControl.php?op=select1", function(data)
    {
     $("#centro").html(data);
	 $("#centro2").html(data);
	 $("#centrobus").html(data);	 
    })
	
	$("#centro").on('change', function () {
		
	var fecha=$("#fecha").val();
    var contrato=$("#centro").val();
	if(fecha!="" && contrato!=""){
	 $.post("../Control/DatosControl.php?op=select2",{fecha:fecha,centro:contrato}, function(data)
    {
	
     $("#categoria").html(data);
    })	
	}	
	  });

 	$('#centro').select2({
    width: '100%' ,
    
});
	
$('#centrobus').select2({
    width: '100%' ,
    
});
	
	$('#centro2').select2({
    width: '100%' ,
    
});	
 	$('#categoria').select2({
    width: '100%' ,
    
});
	
jQuery('input[type=file]').change(function(){
 var filename = jQuery(this).val().split('\\').pop();
 var idname = jQuery(this).attr('id');
 console.log(jQuery(this));
 console.log(filename);
 console.log(idname);
 jQuery('span.'+idname).next().find('span').html('Cargado <i class="fa fa-check"></i>');
});	

}


//Función mostrar formulario
function mostrarform(flag,fr)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
            $('#formularioregistros').show();
            $('#listadoregistros').hide();
            $('#btnagregar').hide();
            $("#btnGuardar").prop("disabled",false);
		    $("#btnGuardar2").prop("disabled",false);
		    formv(fr);
    }
    else
    {		
             $("#btnGuardar").prop("disabled",false);
             $('#formularioregistros').hide();
             $('#btnagregar').show();
             $('#listadoregistros').show();
           
             
    }
}

function formv(flag)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
            $('#formregistros').show();
            $('#formrcsv').hide();
    }
    else
    {		
            $('#formregistros').hide();
            $('#formrcsv').show();     
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
	document.getElementById("formrcsv").reset();
	$('#idCargue').val("");
	$('#centro').val("").trigger('change');
	$("#categoria").html("<option value=''>Seleccione Categoría...</option>");
	$('#categoria').val("").trigger('change');
    jQuery('span.miarchivo').next().find('span').html('Archivo CSV <i class="fa fa-cloud-upload"></i>');
}


//Función Listar
function listar()
{
	
	var centro=$("#centrobus").val();
	var fechai=$("#fechainical").val();
	var fechaf=$("#fechafinal").val();
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
                                    url: '../Control/DatosControl.php?op=listar&estado='+estado+'&centrobus='+centro+'&fechainicial='+fechai+'&fechafinal='+fechaf,//pagina que realiza la operación
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
	var fechaActual = new Date();
	const año = fechaActual.getFullYear(); // Obtiene el año
const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexados, por lo que sumamos 1 y aseguramos dos dígitos
const día = String(fechaActual.getDate()).padStart(2, '0'); // Asegura dos dígitos
const fechaFormateada = `${año}-${mes}-${día}`;
	var fecha=$("#fecha").val();
	if(fecha<=fechaFormateada){
	$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formregistros")[0]);
    $.ajax({
            url: "../Control/DatosControl.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
            mostrarform(false,false);
            limpiar();
            tabla.ajax.reload();
            //$(location).attr('href','../Vista/index.html');    
            
            
            
        }
    });
    limpiar();	
	}else{
		alert("Error la fecha selecciona supera a la fecha actual.");
	}
    
}


function guardarCSV(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar2").prop("disabled",true);
    var formData = new FormData($("#formrcsv")[0]);
	$('#btnguard').removeClass('fa-save');
    $('#btnguard').addClass('fa-spinner fa-spin'); 
    $.ajax({
            url: "../Control/DatosControl.php?op=importar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
            mostrarform(false,false);
			$('#btnguard').removeClass('fa-spinner fa-spin');
            $('#btnguard').addClass('fa-save'); 
            limpiar();
            tabla.ajax.reload();
            //$(location).attr('href','../Vista/index.html');    
            
            
            
        }
    });
    limpiar();	
    
}




function mostrar(id)
{
    
    $.post("../Control/DatosControl.php?op=mostrar",{idCargue : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true,true)       
    $("#idCargue").val(data.IDEJECUCIONPEAJE);
    $("#fecha").val(data.FEC_DET_EJE_PEAJE); 
    $('#centro').val(data.ID_EJECUCION_CONTRATO).trigger('change');			 
    $("#totaltrafico").val(data.TOTAL_TRAIFICO); 
	$("#totalrecudo").val(data.TOTAL_RECUDO); 		 
	$("#traficoex").val(data.TOTAL_TRAFICO_EXCLUSIVO); 
	$("#recudoex").val(data.TOTAL_RECUDO_EXCLUSIVO); 
	$("#traficoexentos").val(data.TRAFICO_EXT_LEY); 
	$("#traficoConsecion").val(data.TRAFICO_EXT_CONSECION);
			 
	setTimeout(() => {	 
	$.post("../Control/DatosControl.php?op=select3", function(data)
    {
     $("#categoria").html(data);
	})
	setTimeout(() => {	 
	 $('#categoria').val(data.CATEGORIA).trigger('change');		
    }, 200);	
    }, 500);		 
			 
    }, 1000);      
   
     
    });
    
    
}
function solicitud(id){
    
     bootbox.confirm({
            message: 'Desea enviar solicitud para modificar el registro?',
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
                  $.post("../Control/DatosControl.php?op=Solicitud",{idCargue : id}, function(data)
            {
      bootbox.alert({
                        title: 'Solicitud de envio!',
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


function aprobar(id){
    
     bootbox.confirm({
            message: 'Desea aprobar este registro?',
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
                  $.post("../Control/DatosControl.php?op=AprobarRechazar",{idCargue : id,estadom:3}, function(data)
            {
      bootbox.alert({
                        title: 'Solicitud de aprobación!',
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

function rechazar(id){
    
     bootbox.confirm({
            message: 'Desea rechazar este registro?',
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
                  $.post("../Control/DatosControl.php?op=AprobarRechazar",{idCargue : id,estadom:1}, function(data)
            {
      bootbox.alert({
                        title: 'Solicitud de rechazo!',
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


function Numero(evt) {
    evt = evt || window.event;
	var charCode = evt.keyCode || evt.which;
    var charTyped = String.fromCharCode(charCode).toString();
    if (isNaN(charTyped)) {
     evt.preventDefault();
    }

}

function puntosNumero(numero) {
	   var num = numero.replace(/[A-Za-z]|[.!"#%&/¨¡;:,_-´¡'\=\-*+?^${}()|[\]\\]/g, '');//[]()/\^`|[\]\\]|'¿´+-!"#$%&<>;:_*?=
        //invierte el orden numerico para poner punto cada 3 caracteres
        num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
        //reordena el número para mostrar
        num = num.split('').reverse().join('').replace(/^[\.]/, '');
	    return num;

}

function puntostexto(event){
	var inputElement = event.target;
    var inputId = "#"+inputElement.id;
	var numero=$(inputId).val();
	var num=puntosNumero(numero);
	$(inputId).val(num);
	
}


init();//ejecuta la función init