var tabla;//variable global
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false);
	$('#nos').val("0");
    listar(1);//lista 
   
 
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	
	 $("#formotrosi").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardarots(e);//guarda o edita el articulo
    });
	
	$.post("../Control/ContratoControl.php?op=select", function(data)
    {
     $("#uen").html(data);
    });
	
	$.post("../Control/ContratoControl.php?op=select2", function(data)
    {
     $("#empresa").html(data);
    });
	
	$.post("../Control/ContratoControl.php?op=select3", function(data)
    {
     $("#Cliente").html(data);
    });
	$.post("../Control/ContratoControl.php?op=select4", function(data)
    {
     $("#EstadoContrato").html(data);
    });
	
	
	$('#uen').select2({
    width: '100%' ,
    
});
	
	$('#empresa').select2({
    width: '100%' ,
    
});
	
	$('#Cliente').select2({
    width: '100%' ,
    
});
	
	$('#EstadoContrato').select2({
    width: '100%' ,
    
});
 

}


//Función mostrar formulario
function mostrarform(flag)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
            $('#formularioregistros').show();
		    mostrarformu(true);
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

function mostrarformu(flag)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
      $('#formregistros').show();  
	  $('#formotrosi').hide();	
    }
    else
    {		
      $('#formotrosi').show();  	
	  $('#formregistros').hide();
	  $("#btnGuardar2").prop("disabled",false);	
             
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
	document.getElementById("formotrosi").reset();
	$('#uen').val("").trigger('change');
	$('#empresa').val("").trigger('change');
	$('#Cliente').val("").trigger('change');
	$('#EstadoContrato').val("").trigger('change');
	$('#nos').val("0");
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
                                    url: '../Control/ContratoControl.php?op=listar&estado='+estado,//pagina que realiza la operación
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
            url: "../Control/ContratoControl.php?op=guardar",
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



function guardarots(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar2").prop("disabled",true);
    var formData = new FormData($("#formotrosi")[0]);
    $.ajax({
            url: "../Control/ContratoControl.php?op=OTROSI",
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
    
    $.post("../Control/ContratoControl.php?op=mostrar",{Idcontrato : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true)       
    $("#Idcontrato").val(data.IDcontrato);
    $("#nombre").val(data.NombreContrato);             
    $('#uen').val(data.IDUenContratro).trigger('change');   
	$('#empresa').val(data.IDEmpresaContrato).trigger('change');   
    $('#Cliente').val(data.IDClienteContrato).trigger('change');   
	$('#EstadoContrato').val(data.idestadocontrato).trigger('change'); 
			 
	$('#fechainicio').val(data.FechaInicio); 
	$('#fechafinal').val(data.fechaFinal); 
	$('#valorm').val(puntosNumero(data.ValorMensualContrato.toString())); 
	$('#valort').val(puntosNumero(data.ValorTotalContrato.toString())); 
	$('#nos').val(data.NoOS); 	
	$('#tarifa').val(data.TipoTarifa); 
	$('#objeto').val(data.ObjetoContrato); 		 
                  
    }, 1000);      
   
     
    });
    
    
}


function otsagregar(id,nos){
    
     bootbox.confirm({
            message: "Desea agregar el otro si No "+nos,
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
                mostrarform(true);
				mostrarformu(false);
				$("#Idcontrato2").val(id)	
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