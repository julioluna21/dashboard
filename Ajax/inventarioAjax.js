var tabla,estado,vehiculo="";//variable global
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false);
	estado=1;
    listar(1);//lista 
 
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	
	 $.post("../Control/inventarioControl.php?op=select2", function(data)
    {
	 $("#elemento").html(data);	 
    });
	
	vhslc();
   	 
	
$('#vehiculo').select2({
    width: '100%' ,
    
});	

$('#elemento').select2({
    width: '100%' ,
    
});	
	

}

function vhslc()
{
	
	$.post("../Control/inventarioControl.php?op=select", function(data)
    {
    $("#vehiculo").html(data);;	 
    });	 
   
}


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
	$('#elemento').val("").trigger('change');
	$('#vehiculo').val("").trigger('change');
	$("#vehiculo").prop("disabled",false);
	$('#idinventario').val("");	
	vehiculo="";
	limpiartabla();
	 vhslc();
	
}


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
                                    url: '../Control/inventarioControl.php?op=listar&estado='+estado,//pagina que realiza la operación
                                    type : "get",//tipo de envio de datos
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
    
}
//Función para guardar o editar
function guardar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    var Tabla1 = document.getElementById("tbdetalle");
    if(Tabla1.rows.length>=2){
    var elemento="";
    var cantidad="";
	var estado="";	
	var descripcion="";	
	var id=	$('#idinventario').val();		
    $("#btnGuardar").prop("disabled", true);
    var obj={};    
    for (var i =1; i<Tabla1.rows.length; i++){
    var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida
    elemento=CeldasDeFila[1].textContent.toLowerCase().split(' ').join(''); 
	elemento=$("#"+elemento).val();	
    cantidad=CeldasDeFila[2].textContent.toLowerCase();  
	estado=CeldasDeFila[3].textContent.toLowerCase();  	
	descripcion=CeldasDeFila[4].textContent.toLowerCase();  	
   

    var obj2={
	"idinventario":id, 	
	"vehiculo":vehiculo, 	
    "elemento":elemento,  
    "estadoelemento":estado, 
	"cantidad":cantidad,
	"observacion":descripcion,	
    }; 
    obj[i-1]=obj2;    
    }
    
    $.ajax({
        url: "../Control/inventarioControl.php?op=guardar",
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





function mostrar(id)
{
    
    $.post("../Control/inventarioControl.php?op=mostrar",{idinventario : id}, function(data)
    {
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
		var data = JSON.parse(data);
       setTimeout(() => {
       bootbox.hideAll();	 
     mostrarform(true);
	$("#idinventario").val(id);	   
    var vh="";	
	var placa="";		   
     data.iformacion.forEach(function(datos) {
	 vh=datos.ID_VEH;	 
	 placa=datos.PLACA_VEH;		 
	 var html='<tr><td><input type="hidden"  id="'+datos.NOMBRE_ELEMENTO.toLowerCase().split(' ').join('')+'"  value="'+datos.ID_ELEMENTOINV+'"><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td>'+datos.NOMBRE_ELEMENTO+'</td></tr><tr><td>'+datos.CANTIDADINV+'</td></tr><tr><td>'+datos.ESTADOINV+'</td></tr><tr><td>'+datos.OBSERVACIONINV+'</td></tr>'
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;  
		 
  });	
	$('#vehiculo').html("<option value='"+vh+"'>"+placa+"</option>");
    $('#vehiculo').val(vh).trigger('change');	 
	$("#vehiculo").prop("disabled",true);			 
    }, 1000);      
   
     
    });
    
    
}


function agregartabla(){
    var vehiculov=$("#vehiculo").val();
    var cantidad=$("#cantidad").val();
	var estado=$("#estadoelemento").val();
	var descripcion=$("#observacion").val();
	var elemento=$("#elemento").val();
    var select = document.getElementById("elemento");
    var textoS = select.options[select.selectedIndex].text;
    if(vehiculov!="" && cantidad!="" && estado!=""){
	if(vehiculo==""){
	vehiculo=$("#vehiculo").val();	
	$("#vehiculo").prop("disabled",true);
	}		
	
	if(validar()){
	$('#elemento').val("").trigger('change');
	$("#cantidad").val("");
	$("#estadoelemento").val("");	
	$("#observacion").val("");	
    var html='<tr><td><input type="hidden"  id="'+textoS.toLowerCase().split(' ').join('')+'"  value="'+elemento+'"><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td>'+textoS+'</td></tr><tr><td>'+cantidad+'</td></tr><tr><td>'+estado+'</td></tr><tr><td>'+descripcion+'</td></tr>'
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;
	   
	}else{
		   alert("Ya se agrego este elemento para este registro")
	   }	    
    }else{
       bootbox.alert("todos los campos son obligatorios");  
    }

}


function validar(){
	var Tabla1 = document.getElementById("tbdetalle");
    if(Tabla1.rows.length>=2){
	var select = document.getElementById("elemento");
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

function anular(idinventario){
    
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
                  $.post("../Control/inventarioControl.php?op=desactivar",{idinventario : idinventario}, function(data)
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
function activar(idinventario){
    
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
                  $.post("../Control/inventarioControl.php?op=activar",{idinventario : idinventario}, function(data)
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

function generarpdf(id,placa){
     redirect_by_post('../Vista/GenerarInventario.php', {
        idinventario: id,
		placa: placa, 
    }, true);

}

function redirect_by_post(purl, pparameters, in_new_tab) {
    pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
    in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
    var form = document.createElement("form");
    $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", purl).attr("method", "post").attr("enctype", "multipart/form-data");
    if (in_new_tab) {
        $(form).attr("target", "_blank");
    }
    $.each(pparameters, function(key) {
        $(form).append('<input type="text" name="' + key + '" value="' + this + '" />');
    });
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    return false;
}



init();//ejecuta la función init