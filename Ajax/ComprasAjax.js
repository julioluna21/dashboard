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

	
	 $.post("../Control/PresupuestoControl.php?op=select", function(data)
    {
    $("#proveedor").html(data);
    });
	
	$.post("../Control/PresupuestoControl.php?op=select2", function(data)
    {
    $("#uen").html(data);
    });
	
	$.post("../Control/PresupuestoControl.php?op=select3", function(data)
    {
    $("#empresa").html(data);
    });
	

$('#proveedor').select2({
    width: '100%' ,
    
});
	
$('#uen').select2({
    width: '100%' ,
    
});
	
$('#empresa').select2({
    width: '100%' ,
    
});	


}


//Función mostrar formulario
function mostrarform(flag)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
            $('#formularioregistros').show();
		    $('#contrato').hide();
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
	$("#idpresupuesto").val("");
	$('#proveedor').val("").trigger('change');
	$('#uen').val("").trigger('change');
	$('#empresa').val("").trigger('change');		 
	//$("#aplica").val("");  	
    document.getElementById("formregistros").reset();
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
                                    url: '../Control/CompraControl.php?op=listar&estado='+estado,//pagina que realiza la operación
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
            url: "../Control/CompraControl.php?op=guardar",
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
    
    $.post("../Control/CompraControl.php?op=mostrar",{idpresupuesto : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
     setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true)       
    $("#idpresupuesto").val(data.IDCOMPRA);
	$('#proveedor').val(data.IDPROVEEDORCOMPRA).trigger('change');
	$('#uen').val(data.IDUENCOMPRA).trigger('change');
	$('#empresa').val(data.IDEMPRESACOMPRA).trigger('change');		   		 
	$("#fechaI").val(data.FECHACOMPRA); 
    $("#valor").val(data.VALORCOMPRA);
	$("#detalle").val(data.NOMBRE_ELEMENTO); 		               
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
                  $.post("../Control/CompraControl.php?op=desactivar",{idpresupuesto : id}, function(data)
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
                  $.post("../Control/CompraControl.php?op=activar",{idpresupuesto : id}, function(data)
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