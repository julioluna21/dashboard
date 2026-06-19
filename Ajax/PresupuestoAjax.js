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
	
	$("#formrenovar").on("submit",function(e)//e = variable que contiene el objeto
    {
            renovarR(e);//guarda o edita el articulo
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
	
	 $.post("../Control/PresupuestoControl.php?op=select4", function(data)
    {
     $("#Centroop").html(data);	 
    });
	
	$.post("../Control/ItemControl.php?op=select", function(data)
    {
     $("#Item").html(data);	 
    });
	
	
	
$("#aplica").on('change', function(){
var aplica=$("#aplica").val();
if(aplica=="SI"){
	$('#contrato').show();
}else{
	$('#contrato').hide();
}	
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
	
$('#Centroop').select2({
    width: '100%' ,
    
});	
	
$('#Item').select2({
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
	limpiartabla();
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
                                    url: '../Control/PresupuestoControl.php?op=listar&estado='+estado,//pagina que realiza la operación
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
    if(Tabla1.rows.length>=3){
	$("#btnGuardar").prop("disabled",true);
		
	var obj={};    
    for (var i =1; i<Tabla1.rows.length-1; i++){
    var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida
	centro=CeldasDeFila[1].textContent.toString();	
    elemento=CeldasDeFila[2].textContent.toString(); 
	cantidad=CeldasDeFila[3].textContent.toLowerCase();   	
    valorU=CeldasDeFila[4].textContent.toLowerCase();
	valorT=CeldasDeFila[5].textContent.toLowerCase();	
    idcentro=$("#"+centro.replace(/\s+/g, '')).val();
	iditem=$("#"+elemento.replace(/\s+/g, '')).val();	
    var obj2={
    "idcentro":idcentro, 
	"iditem":iditem,
	"cantidad":cantidad,
	"valorU":valorU,
	"valorT":valorT,	
    }; 
    obj[i-1]=obj2;    
    }	
	// Convertimos el array de objetos a JSON y lo agregamos al FormData
    var formData = new FormData($("#formregistros")[0]);
	formData.append('DatosDetalle', JSON.stringify(obj));
		
	alert(JSON.stringify(obj));	
    $.ajax({
            url: "../Control/PresupuestoControl.php?op=guardar",
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
		
		
	}else{
		alert("El presupuesto no tiene Items agregados");
	}
}


function renovarR(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar2").prop("disabled",true);
    var formData = new FormData($("#formrenovar")[0]);
    $.ajax({
            url: "../Control/PresupuestoControl.php?op=Renovar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
            $('#modal-renovar').modal('hide');
			$("#idpresupuesto2").val("");
			$("#fechaI2").val(""); 
			$("#btnGuardar2").prop("disabled",false);
            
        }
    });
    limpiar();
}




function mostrar(id)
{
    
    $.post("../Control/PresupuestoControl.php?op=mostrar",{idpresupuesto : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
     setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true)       
    $("#idpresupuesto").val(data.general.ID_PRESUPUESTO);
	$('#proveedor').val(data.general.ID_PROVEEDOR_PRS).trigger('change');
	$('#uen').val(data.general.UNIDAD_NEGOCIO_PRS).trigger('change');
	$('#empresa').val(data.general.EMPRESA_PRS).trigger('change');		 
	$("#aplica").val(data.general.APLICA_CONTRATO);  		 
    $("#contrato").val(data.general.NOCONTRATO_PRS); 
	$("#cobro").val(data.general.TIEMPO_COBRO);  
	$("#fechaI").val(data.general.FECHA_INICIO); 
    $("#valor").val(data.general.VALOR_PRESUPUESTO); 
	$("#tipoPago").val(data.general.TIPO_PAGO); 
	$("#detalle").val(data.general.DETALLE_PRESUPUESTO); 
	if(data.general.APLICA_CONTRATO=="SI"){
	$('#contrato').show();
}else{
	$('#contrato').hide();
}		 
	 //alert(JSON.stringify(data.detalle)); 
	data.detalle.forEach(function(datos) {	 
	var html='<tr><td><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td><input type="hidden" id="'+datos.NombreCentroOP.replace(/\s+/g, '')+'" value="'+datos.CENTRO_OPERATIVO+'">'+datos.NombreCentroOP+'</td></tr><tr><td><input type="hidden" id="'+datos.NOMBRE_ITEM.replace(/\s+/g, '')+'" value="'+datos.ITEM_PRESUPUESTO+'">'+datos.NOMBRE_ITEM+'</td></tr><tr><td>'+datos.CANTIDAD+'</td></tr><tr><td>'+datos.VALOR_UNITARIO+'</td></tr><tr><td>'+datos.VALOR_TOTAL+'</td></tr>';
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;		 
	TotalTabla();	
  });	 	
		 
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
                  $.post("../Control/PresupuestoControl.php?op=desactivar",{idpresupuesto : id}, function(data)
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
                  $.post("../Control/PresupuestoControl.php?op=activar",{idpresupuesto : id}, function(data)
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

function renovar(id) {
$("#idpresupuesto2").val(id);   
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

function sinPuntos(numer) {
    //quita puntos y comas
    var num = numer.replace(/[A-Za-z]|[.!"#%&/¨¡;:,_-´¡'\=\-*+?^${}()|[\]\\]/g, '');//[]()/\^`|[\]\\]|'¿´+-!"#$%&<>;:_*?=

    return num;
}


function agregartabla(){
	var select1 = document.getElementById("Item");
    var textoitem = select1.options[select1.selectedIndex].text;
	var select2 = document.getElementById("Centroop");
    var textocentro = select2.options[select2.selectedIndex].text;
	var centro=$("#Centroop").val();
    var elemento=$("#Item").val();
    var cantidad=$("#cantidad").val();
	var ValorU=$("#valorU").val();
	var ValorU2=sinPuntos(ValorU);
	var ValorT2=ValorU2*cantidad;
	if(centro!="" && elemento!="" && cantidad!="" && ValorU!=""){
	var html='<tr><td><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td><input type="hidden" id="'+textocentro.replace(/\s+/g, '')+'" value="'+centro+'">'+textocentro+'</td></tr><tr><td><input type="hidden" id="'+textoitem.replace(/\s+/g, '')+'" value="'+elemento+'">'+textoitem+'</td></tr><tr><td>'+cantidad+'</td></tr><tr><td>'+puntosNumero(ValorU2.toString())+'</td></tr><tr><td>'+puntosNumero(ValorT2.toString())+'</td></tr>'
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;	
    TotalTabla();
    $("#cantidad").val("");
	$("#valorU").val("");
	$('#Item').val("").trigger('change');
	$('#Centroop').val("").trigger('change');	
	}
}


function TotalTabla(){
	
	
	var Tabla1 = document.getElementById("tbdetalle");
	var op=0;
	if(Tabla1.rows.length>=3){
	Tabla1.deleteRow(Tabla1.rows.length-1);	
	}else{
	var CeldasDeFila = Tabla1.rows[1].getElementsByTagName('td');//datos necesarios para la tabla detalle salida	
	var vt=CeldasDeFila[4].textContent.toLowerCase();
	if(vt=="total:"){
	Tabla1.deleteRow(Tabla1.rows.length-1);		
	}	
		
	}
	
	for (var i =1; i<=(Tabla1.rows.length-1); i++){
	var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida	
	var vt=CeldasDeFila[5].textContent.toLowerCase();
    vt=sinPuntos(vt.toString());
    op=parseInt(op)+parseInt(vt);		
	}
	
	var html='<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td>TOTAL:</td></tr><tr><td>'+puntosNumero(op.toString())+'</td></tr>'
   document.getElementById("tbdetalle").insertRow((Tabla1.rows.length)).innerHTML=html;	
   $("#valor").val(op);
	

}



$(document).on('click', '.bt-borra', function (event) {
    event.preventDefault();
    $(this).closest('tr').remove();
	TotalTabla();
});

function limpiartabla(){
    var Tabla1 = document.getElementById("tbdetalle");
    for (var i = Tabla1.rows.length; i>1 ; i--){
    Tabla1.deleteRow(i-1);
    
    }
  
}







init();//ejecuta la función init