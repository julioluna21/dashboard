var tabla,estado,proyecto="";//variable global
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
	
		
    $("#formeditar").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardareditar(e);//guarda o edita el articulo
    });
			
	 $.post("../Control/DisponibiliodadFlotaControl.php?op=select", function(data)
    {
     $("#proyecto").html(data);
	 $("#proyecto2").html(data);	 
	 $("#centrobus").html(data);	 
    });
	
	 $.post("../Control/DisponibiliodadFlotaControl.php?op=select2", function(data)
    {
	 $("#vehiculom").html(data);	 
    });
		
	vhslc();
   	 

 	$('#proyecto').select2({
    width: '100%' ,
    
});
	
	
$('#centrobus').select2({
    width: '100%' ,
    
});
	
	$('#proyecto2').select2({
    width: '100%' ,
    
});	

$('#vehiculo').select2({
    width: '100%' ,
    
});	
	

}

function vhslc()
{
	
	$.post("../Control/DisponibiliodadFlotaControl.php?op=select3", function(data)
    {
    $("#vehiculo").html(data);;	 
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
		    $('#formeditar').hide();
    }else if(flag==2)
    {		
            $('#formregistros').hide();
		    $('#formeditar').show();  
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
	$('#idflota').val("");
	$('#vehiculo2').val("");
	$('#proyecto').val("").trigger('change');
	$('#vehiculo').val("").trigger('change');
	$("#proyecto").prop("disabled",false);
	proyecto="";
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
                                    url: '../Control/DisponibiliodadFlotaControl.php?op=listar&estado='+estado+'&centrobus='+centro,//pagina que realiza la operación
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
    var Tabla1 = document.getElementById("tbdetalle");
    if(Tabla1.rows.length>=2){
    var vehiculo="";
    var fecha="";	
    $("#btnGuardar").prop("disabled", true);
    var obj={};    
    for (var i =1; i<Tabla1.rows.length; i++){
    var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida
    vehiculo=CeldasDeFila[1].innerHTML.toLowerCase(); 
	vehiculo=$("#"+vehiculo).val();	
    fecha=CeldasDeFila[2].innerHTML.toLowerCase();   

    var obj2={
    "fecha":fecha,  
    "proyecto":proyecto, 
	"vehiculo":vehiculo,	 
    }; 
    obj[i-1]=obj2;    
    }
    
    $.ajax({
        url: "../Control/DisponibiliodadFlotaControl.php?op=guardar",
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
		  vhslc();	
        }
    });      
      
    }else{
       bootbox.alert("Debe gregar registros en la tabla"); 
    }	
	
    
}




function guardareditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar3").prop("disabled",true);
    var formData = new FormData($("#formeditar")[0]); 
    $.ajax({
            url: "../Control/DisponibiliodadFlotaControl.php?op=guardar",
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
    
    $.post("../Control/DisponibiliodadFlotaControl.php?op=mostrar",{idflota : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true,2);    
	$("#vehiculom").prop("disabled",false);		 
    $("#idflota").val(data.ID_DISPONIBILIDAD);
    $("#fecha2").val(data.FECHA_INICIO_DIS); 
    $('#proyecto2').val(data.ID_PROYECTO_DIS).trigger('change');	
	$('#vehiculo2').val(data.ID_VEHICULOS_DIS);	
	$('#vehiculom').val(data.ID_VEHICULOS_DIS);			 
	$("#vehiculom").prop("disabled",true);			 
    }, 1000);      
   
     
    });
    
    
}


function agregartabla(){
    var vehiculo=$("#vehiculo").val();
    var fecha=$("#fecha").val();
    var select = document.getElementById("vehiculo");
    var textoS = select.options[select.selectedIndex].text;
    if(vehiculo!="" && fecha!=""){
	if(proyecto==""){
	proyecto=$("#proyecto").val();	
	$("#proyecto").prop("disabled",true);	
	}		
	
	if(validar()){
	$('#vehiculo').val("").trigger('change');
	$("#fecha").val("");	
    var html='<tr><td><input type="hidden"  id="'+textoS.toLowerCase()+'"  value="'+vehiculo+'"><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td>'+textoS+'</td></tr><tr><td>'+fecha+'</td></tr>'
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;
	   
	}else{
		   alert("Ya existe un registro para el vehiculo")
	   }	    
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




$(document).on('click', '.bt-borra', function (event) {
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