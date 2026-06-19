var tabla,tabla2;//variable global
var anio,mes,estado=5;//variable global
var meses=["ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"];  
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false,true);
    const year = new Date().getFullYear();
	$("#anioSe").val(year);
	listar(year);
   
 
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	
	
  $("#formestado").on("submit",function(e)//e = variable que contiene el objeto
    {
            estadoCambio(e);//guarda o edita el articulo
    });
	
	 $("#formEjecutado").on("submit",function(e)//e = variable que contiene el objeto
    {
            ejecutado(e);//guarda o edita el articulo
    });
	
	$("#formenlace").on("submit",function(e)//e = variable que contiene el objeto
    {
            AgregarEnlace(e);//guarda o edita el articulo
    });

	
	$.post("../Control/presupuestoFlotaControl.php?op=select", function(data)
    {
     $("#tipo").html(data);
    });
	
	$.post("../Control/NovedadFlotaControl.php?op=select", function(data)
    {
     $("#vehiculo").html(data);
    });
	
 $("#vehiculo").on("change",function(e)//e = variable que contiene el objeto
    {
	 var idvh=$("#vehiculo").val();
	 if(idvh!=""){
	 $.post("../Control/presupuestoFlotaControl.php?op=mostrarVH",{vehiculo:idvh},function(data){
     data = JSON.parse(data);
	 $('#proyecto').val(data.nombreProyecto); 
	 $('#servicio').val(data.NOM_TIPO_VEHICULO);	
	 $("#idproyecto").val(data.Idproyctos);	 
	 });	 
	 }
           
 });
	
 $("#anioSe").on("change",function(e)//e = variable que contiene el objeto
    {
	var aniose=$('#anioSe').val(); 
	listar(aniose); 
           
 });	
		
	
$('#tipo').select2({
    width: '100%',
    
});
 
$('#vehiculo').select2({
    width: '100%',
    
});	

}


//Función mostrar formulario
function mostrarform(flag,list)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
            $('#formularioregistros').show();
            $('#listadoregistros').hide();
            $('#btnu').hide();
	        $('#btvolver').hide();	
            $("#btnGuardar").prop("disabled",false);
    }
    else
    {		
            $("#btnGuardar").prop("disabled",false);
            $('#formularioregistros').hide();
            $('#btnagregar').show();
            $('#listadoregistros').show();
		    if(list){
			$('#btnu').show();
	        $('#btvolver').hide();	
			$("#inicial").show();
			$("#Gestion").hide();	
			}else{
			$('#btvolver').show();
	        $('#btnu').hide();		
			$("#Gestion").show();
			$("#inicial").hide();		
			}
           
             
    }
}


//Función cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false,true);
	tabla.ajax.reload();
}


//Función limpiar, pone el formulario en blanco
function limpiar()
{
    document.getElementById("formregistros").reset();
	$('#vehiculo').val("").trigger('change');
	$('#tipo').val("").trigger('change');
	$('#proyecto').val("");
	$("#idPresupuesto").val("");
}
//Función Listar
function listar(anio)
{
    tabla=$('#tbmeses').dataTable(//Carga variable con datos datatable
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
                                    url: '../Control/presupuestoFlotaControl.php?op=listar',//pagina que realiza la operación
                                    type : "POST",//tipo de envio de datos
								    data:{anio:anio},
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[ 1, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
    
}

function listarGeneral(mes,anio){
this.anio=anio;	
this.mes=mes;
mostrarform(false,false);	
listarGn();	
}

function listarGn()
{
	//alert(anio);

	$("#anio2").val(anio);
	$("#mes2").val(meses[mes-1]);
    tabla2=$('#tbllistado').dataTable(//Carga variable con datos datatable
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
                                    url: '../Control/presupuestoFlotaControl.php?op=listarGestion',//pagina que realiza la operación
                                    type : "post",//tipo de envio de datos
								    data:{estado:estado,anio:anio,mes:mes},
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
            url: "../Control/presupuestoFlotaControl.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
            mostrarform(false,true);
            limpiar();
            tabla.ajax.reload();
            //$(location).attr('href','../Vista/index.html');    
            
            
            
        }
    });
    limpiar();
}



function estadoCambio(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar2").prop("disabled",true);
    var formData = new FormData($("#formestado")[0]);
    $.ajax({
            url: "../Control/presupuestoFlotaControl.php?op=Estados",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
			$('#modal-estado').modal('hide');
			$('#Vestado').val("");
			$('#enlace').val("");
			$("#btnGuardar2").prop("disabled",false);
            tabla2.ajax.reload();
            //$(location).attr('href','../Vista/index.html');    
            
            
            
        }
    });
  
}


function ejecutado(e)
{
       e.preventDefault(); //No se activará la acción predeterminada del evento
       $("#btnGuardar3").prop("disabled",true);
       var formData = new FormData($("#formEjecutado")[0]);
       $.ajax({
            url: "../Control/presupuestoFlotaControl.php?op=Estados",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
			$('#modal-ejecutado').modal('hide');
			$('#valorej').val("");
			$('#Vestado2').val("");
			$("#btnGuardar3").prop("disabled",false);
            tabla2.ajax.reload();
            //$(location).attr('href','../Vista/index.html');    
            
            
            
        }
    });
  
}


function AgregarEnlace(e)
{
       e.preventDefault(); //No se activará la acción predeterminada del evento
       $("#btnGuardar4").prop("disabled",true);
       var formData = new FormData($("#formenlace")[0]);
       $.ajax({
            url: "../Control/presupuestoFlotaControl.php?op=enlace",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
			$('#modal-enlace').modal('hide');
			$('#enlace2').val("");
			$("#btnGuardar4").prop("disabled",false);
            tabla2.ajax.reload();
            //$(location).attr('href','../Vista/index.html');    
            
            
            
        }
    });
  
}




function mostrar(idPresupuesto)
{
    
    $.post("../Control/presupuestoFlotaControl.php?op=mostrar",{idPresupuesto : idPresupuesto}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true)       
    $("#idPresupuesto").val(data.ID_PRESUPUESTO_FLOTA);
    $("#idproyecto").val(data.PROYECTO_PRESUPUESTO);             
    $('#anio').val(data.ANIO_PRESUPUESTO); 
	$('#mes').val(data.MES_PRESUPUESTO); 		 
    $('#valorp').val(data.VALOR_PRESUPUESTO); 
	$('#detalle').val(data.DESCRIPCION_PRESUPUESTO); 
	$('#proyecto').val(data.nombreProyecto); 
	$('#servicio').val(data.NOM_TIPO_VEHICULO); 
	$('#tipomantenimiento').val(data.MANTENIMIENTO_PRESUPUESTO); 		 
	$('#tipo').val(data.TIPO_FALLA_PRESUPUESTO).trigger('change');   
	$('#vehiculo').val(data.ID_VEHICULO_PRESUPUESTO).trigger('change'); 
    if(data.ESTADO_PRESUPUESTO==4){
	 $('#rq').val(data.RQ_PRESUPUESTO); 
	 $('#oc').val("N/D");
	 $('#valorEj').val("$O"); 
	 $("#btnGuardar").prop("disabled",true);	
	}else if(data.ESTADO_PRESUPUESTO==3){
	 $('#rq').val(data.RQ_PRESUPUESTO); 
	 $('#oc').val(data.OC_PRESUPUESTO);
	 $('#valorEj').val("$O"); 	
	$("#btnGuardar").prop("disabled",true);	
	}else if(data.ESTADO_PRESUPUESTO==2 || data.ESTADO_PRESUPUESTO==1){
	 $('#rq').val(data.RQ_PRESUPUESTO); 
	 $('#oc').val(data.OC_PRESUPUESTO);
	 $('#valorEj').val(data.VALOR_EJECUTADO); 
	$("#btnGuardar").prop("disabled",true);	
	}else{
	 $('#rq').val("N/D"); 
	 $('#oc').val("N/D");
	 $('#valorEj').val("$O"); 
	 $("#btnGuardar").prop("disabled",false);	
	}		 
    }, 1000);      
   
     
    });
    
    
}
function anular(idPresupuesto){
    
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
                  $.post("../Control/presupuestoFlotaControl.php?op=desactivar",{idPresupuesto : idPresupuesto}, function(data)
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
function activar(idPresupuesto){
    
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
                  $.post("../Control/presupuestoFlotaControl.php?op=activar",{idPresupuesto : idPresupuesto}, function(data)
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

function Inventario(idPresupuesto){
    
     bootbox.confirm({
            message: "Desea agregar este registro al apartado de inventario?",
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
                  $.post("../Control/presupuestoFlotaControl.php?op=inventario",{idPresupuesto : idPresupuesto, estado:2}, function(data)
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
         tabla2.ajax.reload();
        
    });
                }
            }
        });
    
}

function Ejecutado(idPresupuesto){
    
     bootbox.confirm({
            message: "Desea dar por finalizado este registro?",
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
                  $.post("../Control/presupuestoFlotaControl.php?op=inventario",{idPresupuesto : idPresupuesto, estado:1}, function(data)
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
         tabla2.ajax.reload();
        
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

function sinPuntos(numer) {
    //quita puntos y comas
    var num = numer.replace(/[A-Za-z]|[.!"#%&/¨¡;:,_-´¡'\=\-*+?^${}()|[\]\\]/g, '');//[]()/\^`|[\]\\]|'¿´+-!"#$%&<>;:_*?=

    return num;
}

function puntostexto(event){
	var inputElement = event.target;
    var inputId = "#"+inputElement.id;
	var numero=$(inputId).val();
	var num=puntosNumero(numero);
	$(inputId).val(num);
	
}

function agregar(id,op){

	$("#idPresupuesto2").val(id);
	//$("#estado").val(op);
	$('#modal-estado').modal('show');
	/*if(op==4){
    $("#titulo").html("AGREGAR RQ");
	$("#texto").html("Numero RQ");	
	}else{
	$("#titulo").html("AGREGAR OC");
	$("#texto").html("Numero OC");		
	}*/
}

function Ejecucion(id){
	
	$("#idPresupuesto3").val(id);
	$('#modal-ejecutado').modal('show');
}

function enlace(id){
	
	$("#idPresupuesto4").val(id);
	$('#modal-enlace').modal('show');
}


init();//ejecuta la función init