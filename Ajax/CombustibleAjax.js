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
	
	
    $.post("../Control/CombustibleControl.php?op=select", function(data)
    {
     $("#proyecto").html(data);			 
    });
	
	$.post("../Control/NovedadFlotaControl.php?op=select", function(data)
    {
     $("#vehiculo").html(data);
    });
	
	$("#vehiculo").on('change', function () {
	var ID=$("#vehiculo").val();
	$.post("../Control/CombustibleControl.php?op=mostrarProyecto",{vehiculo:ID}, function(data)
    {
	data = JSON.parse(data);	
	$('#proyecto').val(data.ID_PROYECTO_DIS).trigger('change');
    })	
	});
	
	var anota=2000;
	var contenido='<option value="">SELECCIONE...</option>';
	for(i=1; i<=30;i++){
	anota=anota+1;		
	contenido=contenido+'<option value="'+anota+'">'+anota+'</option>';	
	}
	$("#ano").html(contenido);	

 $('#proyecto').select2({
    width: '100%' ,
    
});
	
 $('#vehiculo').select2({
    width: '100%' ,
    
});	
	
 $('#ano').select2({
    width: '100%' ,
    
});	
	
$('#MES').select2({
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
	$('#proyecto').val("").trigger('change');
	$('#ano').val("").trigger('change');
	$('#MES').val("").trigger('change');
	$('#idcombistible').val("");
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
                                    url: '../Control/CombustibleControl.php?op=listar&estado='+estado,//pagina que realiza la operación
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
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formregistros")[0]);
    $.ajax({
            url: "../Control/CombustibleControl.php?op=guardar",
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




function mostrar(id)
{
    
    $.post("../Control/CombustibleControl.php?op=mostrar",{idcombistible : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true)       
    $('#proyecto').val(data.PROYECTO_COMBUSTIBLE).trigger('change');
	$('#vehiculo').val(data.IDVEHUCULOCOMBUSTIBLE).trigger('change');		 
	$('#ano').val(data.ANO_COMBUSTIBLE).trigger('change');
	$('#MES').val(data.MES_COMBUSTIBLE).trigger('change');
	$('#idcombistible').val(data.ID_COMBUSTIBLE);
    $('#combustible').val(data.GALONES);
	$('#kilometros').val(data.KILOMETROS);
	$('#servicios').val(data.SERVICIOS);	
	$('#peajes').val(data.PEJAE);	
	$('#mantenimiento').val(data.MANTENIMIENTO);
    $('#plataConbustible').val(data.DINEROCOMBUSTIBLE);			 
                  
    }, 1000);      
   
     
    });
    
    
}
function anular(id){
    
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
                  $.post("../Control/CombustibleControl.php?op=desactivar",{idcombistible : id}, function(data)
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
function activar(id){
    
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
                  $.post("../Control/CombustibleControl.php?op=activar",{idcombistible : id}, function(data)
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