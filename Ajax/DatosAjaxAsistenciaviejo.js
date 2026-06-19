var tabla,estado,fecha="",centro="";//variable global
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false,false);
	estado=1;
    listar();//lista 
    $("#ambulancia").hide();
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	
   $("#formrcsv").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardarCSV(e);//guarda o edita el articulo
    });
		
    $("#formeditar").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardareditar(e);//guarda o edita el articulo
    });
    
    $("#centro").on("change",function(e)//e = variable que contiene el objeto
    {
     var id=$("#centro").val();
     $.post("../Control/DatosAsistencialesControl.php?op=mostrarProyecto",{centro: id}, function(data){
     data = JSON.parse(data);  
         
    $.post("../Control/DatosAsistencialesControl.php?op=select2",{proyecto:data.Idproyctos},function(data)
    {
	  $("#vehiculo").html(data);	 
    });
          
     });
    });
    
     $("#centro2").on("change",function(e)//e = variable que contiene el objeto
    {
     var id=$("#centro2").val();
     $.post("../Control/DatosAsistencialesControl.php?op=mostrarProyecto",{centro: id}, function(data){
     data = JSON.parse(data);  
         
    $.post("../Control/DatosAsistencialesControl.php?op=select2",{proyecto:data.Idproyctos},function(data)
    {
     $("#vehiculo2").html(data);	 
    });
          
     });
    });
    
    $("#vehiculo").on("change",function(e)//e = variable que contiene el objeto
    {
    var select = document.getElementById("vehiculo");
    if (select.options.length > 0){
    var vh = select.options[select.selectedIndex].text;    
    vh=vh.split("-"); 
    if(vh[1]=="AMBULANCIA" || vh[1]=="ambulancia"){
        $("#ambulancia").show();
    }else{
        $("#ambulancia").hide();
    } 
    }       
    });
			
	 $.post("../Control/DatosAsistencialesControl.php?op=select1", function(data)
    {
     $("#centro").html(data);
	 $("#centro2").html(data);	 
	 $("#centrobus").html(data);	 
    });
	
	 /*$.post("../Control/DatosAsistencialesControl.php?op=select2", function(data)
    {
	
     $("#vehiculo2").html(data);
	  $("#vehiculo").html(data);	 
    });*/
		
	 

 	$('#centro').select2({
    width: '100%' ,
    
});
	
	
$('#centrobus').select2({
    width: '100%' ,
    
});
	
	$('#centro2').select2({
    width: '100%' ,
    
});	
 	$('#vehiculo2').select2({
    width: '100%' ,
    
});
	
 	$('#vehiculo').select2({
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
		     $("#btnGuardar2").prop("disabled",false);
		     $("#btnGuardar3").prop("disabled",false);
             $('#formularioregistros').hide();
             $('#btnagregar').show();
             $('#listadoregistros').show();
           
             
    }
}

function formv(flag)
{
    if (flag==1)
    {//partes de la pagina que se muestran o se ocultan
            $('#formregistros').show();
            $('#formrcsv').hide();
		    $('#formeditar').hide();
    }
    else if(flag==2)
    {		
            $('#formregistros').hide();
		    $('#formeditar').hide();
            $('#formrcsv').show();     
    }else if(flag==3)
    {		
            $('#formregistros').hide();
		    $('#formeditar').show();
            $('#formrcsv').hide();     
    }
}


//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false,false);
}


//Función limpiar, pone el formulario en blanco
function limpiar()
{
    document.getElementById("formregistros").reset();
	document.getElementById("formeditar").reset();
	document.getElementById("formrcsv").reset();
	$('#idCargue').val("");
	$('#idCargue2').val("");
	$('#idCargue3').val("");
	$('#centro').val("").trigger('change');
	$('#vehiculo').val("").trigger('change');
	$("#fecha").prop("disabled",false);
	$("#centro").prop("disabled",false);
	fecha="";
	centro="";
    jQuery('span.miarchivo').next().find('span').html('Archivo CSV <i class="fa fa-cloud-upload"></i>');
    $("#ambulancia").hide();
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
                                    url: '../Control/DatosAsistencialesControl.php?op=listar&estado='+estado+'&centrobus='+centro+'&fechainicial='+fechai+'&fechafinal='+fechaf,//pagina que realiza la operación
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
	if(fecha<=fechaFormateada){
    var Tabla1 = document.getElementById("tbdetalle");
    if(Tabla1.rows.length>=2){
    var vehiculo="";
    var servicio="";
    var teimpoa="";
    var teimpor="";
    var unidadf="";
    var heridosG="";
    var Heridosl="";
    var ilesos="";
    var fallecidos="";
    var servicioc="";
    var usuarion="";    
    $("#btnGuardar").prop("disabled", true);
    var obj={};    
    for (var i =1; i<Tabla1.rows.length; i++){
    var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida
    vehiculo=CeldasDeFila[1].innerHTML.toLowerCase().replace(/\s+/g, '');  
	vehiculo=$("#"+vehiculo).val();	  
    servicio=CeldasDeFila[2].innerHTML.toLowerCase();   
    teimpor=CeldasDeFila[3].innerHTML.toLowerCase();
    teimpoa=CeldasDeFila[4].innerHTML.toLowerCase();    
    unidadf=CeldasDeFila[5].innerHTML.toLowerCase();  
    heridosG=CeldasDeFila[6].innerHTML.toLowerCase();  
    Heridosl=CeldasDeFila[7].innerHTML.toLowerCase();  
    ilesos=CeldasDeFila[8].innerHTML.toLowerCase();
    fallecidos=CeldasDeFila[9].innerHTML.toLowerCase();    
    servicioc=CeldasDeFila[10].innerHTML.toLowerCase();  
    usuarion=CeldasDeFila[11].innerHTML.toLowerCase();  
          
    var obj2={
    "fecha":fecha,  
    "centro":centro, 
	"vehiculo":vehiculo,	
    "servicio":servicio,
    "tiempor":teimpor,    
    "tiempoa":teimpoa,
    "unidadf" : unidadf,
    "heridosG":heridosG,
    "Heridosl": Heridosl,
    "ilesos": ilesos,
    "fallecidos": fallecidos,
    "servicioc":servicioc,
    "usuarion": usuarion    
    }; 
    obj[i-1]=obj2;    
    }
    
    $.ajax({
        url: "../Control/DatosAsistencialesControl.php?op=guardar",
        type: "POST",
        data: {info : obj},
        success: function(datos)
        {
          bootbox.alert(datos);
          //console.log(datos);  
          mostrarform(false);
          tabla.ajax.reload();    
          limpiar();   
          limpiartabla(); 
        }
    });      
      
    }else{
       bootbox.alert("Debe gregar registros en la tabla"); 
    }	
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
            url: "../Control/DatosAsistencialesControl.php?op=importar",
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


function guardareditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar3").prop("disabled",true);
    var formData = new FormData($("#formeditar")[0]); 
    $.ajax({
            url: "../Control/DatosAsistencialesControl.php?op=guardar",
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
    
}




function mostrar(id)
{
    
    $.post("../Control/DatosAsistencialesControl.php?op=mostrar",{idCargue : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true,3)       
    $("#idCargue2").val(data.ID_DET_EJE_SER_FLO);
    $("#fecha2").val(data.FEC_DET_EJE_SERASIS); 
    $('#centro2').val(data.ID_EJECUCION_CONTRATO).trigger('change');	
	$('#vehiculo2').val(data.IDVEH_DETALLE).trigger('change');		 
	$("#servicio2").val(data.CANT_SERVICIOS_ATENDIDOS); 		 
	$("#tiempor2").val(data.TIEMPO_RESPUESTA_SERVICIOS); 
	$("#tiempoa2").val(data.TIEMPO_ATENCION_SERVICIOS);
             
    $("#unidadF2").val(data.UNIDAD_FUNCIONAL); 
	$("#heridosG2").val(data.HERIDOS_GRAVES); 
    $("#heridosL2").val(data.HERIDOS_LEVES); 
    $("#Ilesos2").val(data.ILESOS); 
    $("#Fallecidos2").val(data.FALLECIDOS); 
    $("#ServiciosC2").val(data.SERVICIO_COMUNIDAD); 
    $("#USNvalorados2").val(data.UNF_USAURIO_NO_VALORADO);
    
    setTimeout(() => {
    $('#vehiculo2').val(data.IDVEH_DETALLE).trigger('change');	
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
                  $.post("../Control/DatosAsistencialesControl.php?op=Solicitud",{idCargue : id}, function(data)
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
                  $.post("../Control/DatosAsistencialesControl.php?op=AprobarRechazar",{idCargue : id,estadom:3}, function(data)
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
                  $.post("../Control/DatosAsistencialesControl.php?op=AprobarRechazar",{idCargue : id,estadom:1}, function(data)
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

/*function agregartabla(){
    var select = document.getElementById("vehiculo");
    var vh = select.options[select.selectedIndex].text; 
    vh=vh.split("-");     
    var vehiculo=$("#vehiculo").val();
    var servicio=$("#servicio").val();
    var tempor=$("#tiempor").val();
    var tiempoa=$("#tiempoa").val();
    var unidadf=$("#unidadF").val();
    var heridosG=$("#heridosG").val();
    var heridosL=$("#heridosL").val();
    var ilesos=$("#Ilesos").val();
    var fallecidos=$("#Fallecidos").val();
    var servicioC=$("#ServiciosC").val();
    var usNvalorado=$("#USNvalorados").val();
    var select = document.getElementById("vehiculo");
    var textoS = select.options[select.selectedIndex].text;
    if((vh[1]!="AMBULANCIA" && vehiculo!="" && servicio!="" && tempor!="" && tiempoa!="") || (vh[1]=="AMBULANCIA" && vehiculo!="" && servicio!="" && tempor!="" && tiempoa!="" && unidadf!="" && heridosG!="" && heridosL!="" && ilesos!="" && fallecidos!="" && servicioC!="" && usNvalorado!="")){
        
    if(vh[1]!="AMBULANCIA"){
    unidadf=0;
    heridosG=0;
    heridosL=0;
    ilesos=0;
    fallecidos=0;
    servicioC=0;
    usNvalorado=0;  
    }    
        
	if(centro=="" && fecha==""){
	fecha=$("#fecha").val();
	centro=$("#centro").val();	
	$("#fecha").prop("disabled",true);
	$("#centro").prop("disabled",true);	
	}		
	$.post("../Control/DatosAsistencialesControl.php?op=validar",{fecha :fecha,centro:centro,vehiculo:vehiculo}, function(data)
    {
	if(data){
	if(validar()){
	$('#vehiculo').val("").trigger('change');
    $("#servicio").val("");
    $("#tiempor").val("");
    $("#tiempoa").val("");
    $("#unidadF").val("");
    $("#heridosG").val("");
    $("#heridosL").val("");
    $("#Ilesos").val("");
    $("#Fallecidos").val("");
    $("#ServiciosC").val("");
    $("#USNvalorados").val("");        
    var html='<tr><td><input type="hidden"  id="'+textoS.toLowerCase().replace(/\s+/g, '')+'"  value="'+vehiculo+'"><SPAN title="Cancelar elemto"><button class="btn btn-danger"style="margin:2px"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td>'+textoS+'</td></tr><tr><td>'+servicio+'</td></tr><tr><td>'+tempor+'</td></tr><tr><td>'+tiempoa+'</td></tr><tr><td>'+unidadf+'</td></tr><tr><td>'+heridosG+'</td></tr><tr><td>'+heridosL+'</td></tr><tr><td>'+ilesos+'</td></tr><tr><td>'+fallecidos+'</td></tr><tr><td>'+servicioC+'</td></tr><tr><td>'+usNvalorado+'</td></tr>'
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;
	   
	   }else{
		   alert("Ya existe un registro para el vehiculo")
	   }	
	}else{
		   alert("Ya existe un registro para el vehiculo y fecha seleccionada.")
	   }	
		
	});
        
    }else{
       bootbox.alert("todos los campos son obligatorios");  
    }

}*/

function agregartabla(){
    var select = document.getElementById("vehiculo");
    var vh = select.options[select.selectedIndex].text; 
    vh=vh.split("-");     
    var vehiculo=$("#vehiculo").val();
    //var servicio=$("#servicio").val();
    var tempor=$("#tiempor").val();
    var tiempoa=$("#tiempoa").val();
    var unidadf=$("#unidadF").val();
    var heridosG=$("#heridosG").val();
    var heridosL=$("#heridosL").val();
    var ilesos=$("#Ilesos").val();
    var fallecidos=$("#Fallecidos").val();
    var servicioC=$("#ServiciosC").val();
    var usNvalorado=$("#USNvalorados").val();
    var select = document.getElementById("vehiculo");
    var textoS = select.options[select.selectedIndex].text;
    if((vh[1]!="AMBULANCIA" && vehiculo!="" && tempor!="" && tiempoa!="") || (vh[1]=="AMBULANCIA" && vehiculo!="" && tempor!="" && tiempoa!="" && unidadf!="" && heridosG!="" && heridosL!="" && ilesos!="" && fallecidos!="" && servicioC!="" && usNvalorado!="")){
        
    if(vh[1]!="AMBULANCIA"){
    unidadf=0;
    heridosG=0;
    heridosL=0;
    ilesos=0;
    fallecidos=0;
    servicioC=0;
    usNvalorado=0;  
    }    
        
	if(centro=="" && fecha==""){
	fecha=$("#fecha").val();
	centro=$("#centro").val();	
	$("#fecha").prop("disabled",true);
	$("#centro").prop("disabled",true);	
	}		
	
	$('#vehiculo').val("").trigger('change');
    //$("#servicio").val("");
    $("#tiempor").val("");
    $("#tiempoa").val("");
    $("#unidadF").val("");
    $("#heridosG").val("");
    $("#heridosL").val("");
    $("#Ilesos").val("");
    $("#Fallecidos").val("");
    $("#ServiciosC").val("");
    $("#USNvalorados").val("");        
    var html='<tr><td><input type="hidden"  id="'+textoS.toLowerCase().replace(/\s+/g, '')+'"  value="'+vehiculo+'"><SPAN title="Cancelar elemto"><button class="btn btn-danger"style="margin:2px"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td>'+textoS+'</td></tr><tr><td>'+"1"+'</td></tr><tr><td>'+tempor+'</td></tr><tr><td>'+tiempoa+'</td></tr><tr><td>'+unidadf+'</td></tr><tr><td>'+heridosG+'</td></tr><tr><td>'+heridosL+'</td></tr><tr><td>'+ilesos+'</td></tr><tr><td>'+fallecidos+'</td></tr><tr><td>'+servicioC+'</td></tr><tr><td>'+usNvalorado+'</td></tr>'
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;
        
    }else{
       bootbox.alert("todos los campos son obligatorios");  
    }

}


function validar(){
	var Tabla1 = document.getElementById("tbdetalle");
    if(Tabla1.rows.length>=2){
	var select = document.getElementById("vehiculo");
    var textoS = select.options[select.selectedIndex].text;
	for (var i =1; i<Tabla1.rows.length; i++){
	var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida	
	var vh=CeldasDeFila[1].innerHTML.toLowerCase();
		if(textoS.toLowerCase()==vh.toLowerCase()){
			return false;
		}
	}
		return true;
	}else{
		return true;
	}
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

$(document).on('click', '.btn-danger', function (event) {
    event.preventDefault();
    $(this).closest('tr').remove();
});

function limpiartabla(){
    var Tabla1 = document.getElementById("tbdetalle");
    for (var i = Tabla1.rows.length; i>1 ; i--){
    Tabla1.deleteRow(i-1);
    
    }
  
}


init();//ejecuta la función init