var tabla,tabla2,tabla3,contador=0;//variable global
var meses=["ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"];  
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false, true);
	
	
    $("#anioSe").on("change",function(){
	var anio=$("#anioSe").val();
	listarInicial(anio);	
	});
	
	const year = new Date().getFullYear();
	$("#anioSe").val(year);
	listarInicial(year);
 
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	
	 $.post("../Control/PresupuestoControl.php?op=select4", function(data)
    {
     $("#Centroop").html(data);	 
    });
	
	$.post("../Control/ItemControl.php?op=select", function(data)
    {
     $("#Item").html(data);	 
    });
$('#Centroop').select2({
    width: '100%' ,
    
});	
	
$('#Item').select2({
    width: '100%' ,
    
});		
	
}


//Función mostrar formulario
function mostrarform(flag,op)
{
    if (flag)
    {//partes de la pagina que se muestran o se ocultan
            $('#formularioregistros').show();
            $('#listadoregistros').hide();
            $("#btnGuardar").prop("disabled",false);
    }
    else
    {	
				
             $("#btnGuardar").prop("disabled",false);
             $('#formularioregistros').hide();
             $('#listadoregistros').show();
		     if(op){
			 $('#inicio').show();
			 $('#Gestion').hide();   	 
			 }else{
			 $('#Gestion').show();
			 $('#inicio').hide();		 
			 }   
    }
}

function listarGestionB(anio,mes,estado){
 mostrarform(false,false);		
 listarGestion(anio,mes,estado);
 listarCompras(anio,mes);	
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
	$("#idPresupuesto").val("");
	$("#idGestiongasto").val("");
    document.getElementById("formregistros").reset();
	limpiartabla();
}
//Función Listar
function listarInicial(anio)
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
                                    url: '../Control/ejecucionPresupuestoControl.php?op=listarInicial',//pagina que realiza la operación
                                    type : "POST",//tipo de envio de datos
								    data: {anio:anio},
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 12,//Paginación
        "order": [[ 1, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
    
}

function listarGestion(anio,mes,estado)
{
	$("#anio").val(anio);
    $("#mes").val(meses[mes-1]);	
	$("#mesP").val(mes);
    $("#anioP").val(anio);	
	
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
                                    url: '../Control/ejecucionPresupuestoControl.php?op=listarPresupuesto',//pagina que realiza la operación
                                    type : "POST",//tipo de envio de datos
								    data: {mes:mes,anio:anio,EstadoG:estado},
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

function listarCompras(anio,mes)
{
	
    tabla3=$('#tbllistadoGastos').dataTable(//Carga variable con datos datatable
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
                                    url: '../Control/ejecucionPresupuestoControl.php?op=listarCompra',//pagina que realiza la operación
                                    type : "POST",//tipo de envio de datos
								    data: {mes:mes,anio:anio},
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
    var idpresupuesto=$("#idPresupuesto").val();
	var idgasto=$("#idGestiongasto").val();
    var mes=$("#mesP").val();
	var anio=$("#anioP").val();
	var Valorp=$("#valorPr").val();
	var detalle=$("#detalle").val();	
    $("#btnGuardar").prop("disabled", true);
    var obj={};    
    for (var i =1; i<Tabla1.rows.length-1; i++){
    var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida
	centroop=CeldasDeFila[1].textContent.toString();		
    elemento=CeldasDeFila[2].textContent.toString();	
	cantidad=CeldasDeFila[3].textContent.toLowerCase();   	
    valorU=CeldasDeFila[4].textContent.toLowerCase();
	valorT=CeldasDeFila[5].textContent.toLowerCase();	
    idcentro=$("#"+centroop.replace(/\s+/g, '')).val();
	idelemento=$("#"+elemento.replace(/\s+/g, '')).val();
	var input=CeldasDeFila[6].querySelector("input");
	ejecutadoV=input.value;	
    var obj2={
    "anio":anio, 
	"mes":mes,
	"observacion":detalle,
	"valor":Valorp,
	"elemento":elemento,
	"cantidad":cantidad,
	"valorU":valorU,
	"valotT":valorT,
	"idcentro":idcentro,
	"idelemento":idelemento,
	"valorejecutado":ejecutadoV,	
    }; 
    obj[i-1]=obj2;    
    }
		
	//alert(JSON.stringify(obj));	
    
    $.ajax({
        url: "../Control/ejecucionPresupuestoControl.php?op=guardar",
        type: "POST",
        data: {idpresupuesto:idpresupuesto,idGestiongasto:idgasto,info : obj},
        success: function(datos)
        {
          bootbox.alert(datos);
          //console.log(datos);  
          mostrarform(false,false);
          tabla2.ajax.reload();    
          limpiar();   
        }
    });      
      
    }else{
       bootbox.alert("Debe agregar registros en la tabla"); 
    }	
	
    
}





function mostrarPresupuesto(id)
{
    
    $.post("../Control/ejecucionPresupuestoControl.php?op=mostrarPresupuesto",{idpresupuesto : id}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true);   
	var tiempoC="";		 
	switch(data.general.TIEMPO_COBRO){
		   case '1':
			tiempoC="MESUAL";
		   break;
		   case '2':
			tiempoC="BIMENSUAL";
		   break;
		   case '3':
			tiempoC="TRIMESTRAL";
		   break;
		   case '4':
			tiempoC="SEMESTRAL";
		   break;
		   case '5':
			tiempoC="ANUAL";
		   break;
		   
		   }	
	   var tipoP="";
	   if(data.TIPO_PAGO==1){
	   tipoP="ANTICIPADO";
	   }else{
		  tipoP="VENCIDO";  
	   }	
	$("#idPresupuesto").val(data.general.ID_PRESUPUESTO);  		 
	$("#proveedor").val(data.general.RAZON_SOCIAL);  		 
    $("#unidad").val(data.general.NombreUen); 
	$("#empresa").val(data.general.NombreEmpresa);  
	$("#tiempocobro").val(tiempoC);  	
	$("#tipoCobro").val(tipoP);  			 
    $("#valorPr").val(puntosNumero(data.general.VALOR_PRESUPUESTO.toString()));      
	
	if(data.general.APLICA_CONTRATO=="SI"){
	$("#nContrato").val(data.general.NOCONTRATO_PRS);	
	$("#contrato").show();	
	}else{
	$("#contrato").hide();		
	}		 
	//alert(JSON.stringify(data.detalle)); 
    data.detalle.forEach(function(datos) {	 
	var html='<tr><td><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td><input type="hidden" id="'+datos.NombreCentroOP.replace(/\s+/g, '')+'" value="'+datos.CENTRO_OPERATIVO+'">'+datos.NombreCentroOP+'</td></tr><tr><td><input type="hidden" id="'+datos.NOMBRE_ITEM.replace(/\s+/g, '')+'" value="'+datos.ITEM_PRESUPUESTO+'">'+datos.NOMBRE_ITEM+'</td></tr><tr><td>'+datos.CANTIDAD+'</td></tr><tr><td>'+datos.VALOR_UNITARIO+'</td></tr><tr><td>'+datos.VALOR_TOTAL+'</td></tr><tr><td><input type="text" class="form-control" name="answer_'+datos.ID_DETALLE_PRESUPUESTO+'" id="H_'+datos.ID_DETALLE_PRESUPUESTO+'" min="0"  required="" onkeypress="Numero(event)" onkeyup="ejecutado(event)"></td></tr>';
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;		 
    });
	
	var Tabla1 = document.getElementById("tbdetalle");		 
	var html='<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td>TOTAL:</td></tr><tr><td>0</td></tr><tr><td>0</td></tr>'
   document.getElementById("tbdetalle").insertRow((Tabla1.rows.length)).innerHTML=html;
	TotalTabla();			 
			 
    }, 1000);      
   
     
    });
    
    
}

function mostrarGEstion(id,anio,mes)
{
    
    $.post("../Control/ejecucionPresupuestoControl.php?op=MostrarGestion",{idpresupuesto : id,anio:anio,mes:mes}, function(data)
    {
        
    bootbox.dialog({
        message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        closeButton: false
        });
         setTimeout(() => {
    bootbox.hideAll()
    data = JSON.parse(data);
    mostrarform(true);   
	var tiempoC="";		 
	switch(data.Encabezado.TIEMPO_COBRO){
		   case '1':
			tiempoC="MESUAL";
		   break;
		   case '2':
			tiempoC="BIMESTRAL";
		   break;
		   case '3':
			tiempoC="TRIMESTRAL";
		   break;
		   case '4':
			tiempoC="SEMESTRAL";
		   break;
		   case '5':
			tiempoC="ANUAL";
		   break;
		   
		   }	
	   var tipoP="";
	   if(data.Encabezado.TIPO_PAGO==1){
	   tipoP="ANTICIPADO";
	   }else{
		  tipoP="VENCIDO";  
	   }	
	$("#idGestiongasto").val(data.Encabezado.ID_GESTION);  		 
	$("#proveedor").val(data.Encabezado.RAZON_SOCIAL);  		 
    $("#unidad").val(data.Encabezado.NombreUen); 
	$("#empresa").val(data.Encabezado.NombreEmpresa);  
	$("#tiempocobro").val(tiempoC);  	
	$("#tipoCobro").val(tipoP); 
	if(data.Encabezado.APLICA_CONTRATO=="SI"){
	$("#nContrato").val(data.Encabezado.NOCONTRATO_PRS);	
	$("#contrato").show();	
	}else{
	$("#contrato").hide();		
	}		 
	var num=data.Encabezado.VALOR_PRESUPUESTO;
	num=puntosNumero(num.toString());		 
    $("#valorPr").val(num);		 
	$("#detalle").val(data.Encabezado.OBSERVACION_GESTION); 		 
    data.Detalle.forEach(function(datos) {
	   
	var html='<tr><td><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td><input type="hidden" id="'+datos.NombreCentroOP.replace(/\s+/g, '')+'" value="'+datos.CENTRO_OPDETALLE_GESTION+'">'+datos.NombreCentroOP+'</td></tr><tr><td><input type="hidden" id="'+datos.NOMBRE_ITEM.replace(/\s+/g, '')+'" value="'+datos.ITEM_GESTION_GASTOS+'">'+datos.NOMBRE_ITEM+'</td></tr><tr><td>'+datos.CANTIDAD+'</td></tr><tr><td>'+datos.VALOR_UNITARIO+'</td></tr><tr><td>'+datos.VALOR_TOTAL+'</td></tr><tr><td><input type="text" class="form-control" name="answer_'+datos.ID_DETALLE_PRS+'" id="H_'+datos.ID_DETALLE_PRS+'" min="0"  required="" onkeypress="Numero(event)" onkeyup="ejecutado(event)" value="'+datos.VALOR_EJECUTADO+'"></td></tr>';
    document.getElementById("tbdetalle").insertRow(1).innerHTML=html;	
	//$("#"+datos.ID_DETALLE_PRS).val()	
		
  });
			 
	var Tabla1 = document.getElementById("tbdetalle");		 
	var html='<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td>TOTAL:</td></tr><tr><td>0</td></tr><tr><td>0</td></tr>'
    document.getElementById("tbdetalle").insertRow((Tabla1.rows.length)).innerHTML=html;
	TotalTabla();	
	valorEjecutado();		 
	    
 if(data.Encabezado.ESTADO_ESTION==2){
	 var num=data.Encabezado.VALOR_TOTAL;
	 num=puntosNumero(num.toString());		 
     $("#valorPr").val(num);
     $("#btnGuardar").prop("disabled", true);	 
 }		 
			 
    }, 1000);      
   
     
    });
    
    
}



function cerrarP(id){
    
     bootbox.confirm({
            message: 'Desea dar por finalizado este registro?',
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
                  $.post("../Control/ejecucionPresupuestoControl.php?op=CerrarGestion",{idGestiongasto: id}, function(data)
            {
      bootbox.alert({
                        title: 'Finalizado!',
                        message: data,
                        size: 'small',
                        closeButton: false
         });              
        
     tabla2.ajax.reload();
        
    });
                }
            }
        });
    
}
function cerrar(anio,mes,ValorP,valorT,valorC){
    
     bootbox.confirm({
            message: "Desea dar por finalizado este mes?",
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
                  $.post("../Control/ejecucionPresupuestoControl.php?op=cerrarMes",{anio : anio,mes:mes,Valorp:ValorP,Valort:valorT,ValorC:valorC}, function(data)
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

function agregartabla(){
	contador=contador+1;
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
	var html='<tr><td><SPAN title="Cancelar elemto"><button class="btn btn-secondary bt-borra"style="margin:2px" type="button"  onclick=""><i class="fa fa-times"></i></button></SPAN></td></tr><tr><td><input type="hidden" id="'+textocentro.replace(/\s+/g, '')+'" value="'+centro+'">'+textocentro+'</td></tr><tr><td><input type="hidden" id="'+textoitem.replace(/\s+/g, '')+'" value="'+elemento+'">'+textoitem+'</td></tr><tr><td>'+cantidad+'</td></tr><tr><td>'+puntosNumero(ValorU2.toString())+'</td></tr><tr><td>'+puntosNumero(ValorT2.toString())+'</td></tr><tr><td><input type="text" class="form-control" name="answerc_'+contador+'" id="H_'+contador+'C" min="0"  required="" onkeypress="Numero(event)" onkeyup="ejecutado(event)"></td></tr>'
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
	
	for (var i =1; i<=(Tabla1.rows.length-2); i++){
	var CeldasDeFila = Tabla1.rows[i].getElementsByTagName('td');//datos necesarios para la tabla detalle salida	
	var vt=CeldasDeFila[5].textContent.toLowerCase();
    vt=sinPuntos(vt.toString());
    op=parseInt(op)+parseInt(vt);		
	}
	
	var ta=Tabla1.rows.length;
	var CeldasDeFila = Tabla1.rows[ta-1].getElementsByTagName('td');//datos necesarios para la tabla detalle salida	
	var vt=CeldasDeFila[5].textContent=puntosNumero(op.toString());	
   $("#valor").val(op);
	

}


$(document).on('click', '.bt-borra', function (event) {
    event.preventDefault();
    $(this).closest('tr').remove();
	TotalTabla();
	valorEjecutado();
});

function valorEjecutado(){
	var suma=0;	
const form = document.getElementById("formregistros"); // Selecciona el formulario por su ID
const inputsTexto = form.querySelectorAll('input[type="text"], textarea');
inputsTexto.forEach(input => {
	var Tipoelemento2=input.id.split("_");
	if(Tipoelemento2[0]=="H"){
		if(input.value!=""){
		suma=parseInt(suma)+parseInt(sinPuntos(input.value.toString()));	
		}
	}
    //console.log(`ID: ${input.id}, Valor: ${input.value}`);
});
	var Tabla1 = document.getElementById("tbdetalle");
	var ta=Tabla1.rows.length;
	var CeldasDeFila = Tabla1.rows[ta-1].getElementsByTagName('td');//datos necesarios para la tabla detalle salida	
	var vt=CeldasDeFila[6].textContent=puntosNumero(suma.toString());
	
}

function limpiartabla(){
    var Tabla1 = document.getElementById("tbdetalle");
    for (var i = Tabla1.rows.length; i>1 ; i--){
    Tabla1.deleteRow(i-1);
    
    }
  
}


function ejecutado(evt){
	

var inputElement = event.target;
var Tipoelemento=inputElement.id.split("_");	
var inputId = inputElement.id;	
var numero=$("#"+inputId).val();
var num=puntosNumero(numero);	
$("#"+inputId).val(num);	
//alert(inputId+"----"+Tipoelemento[0]);	
var suma=0;	
const form = document.getElementById("formregistros"); // Selecciona el formulario por su ID
const inputsTexto = form.querySelectorAll('input[type="text"], textarea');
inputsTexto.forEach(input => {
	var Tipoelemento2=input.id.split("_");
	if(Tipoelemento2[0]=="H"){
		if(input.value!=""){
		suma=parseInt(suma)+parseInt(sinPuntos(input.value.toString()));	
		}
	}
    //console.log(`ID: ${input.id}, Valor: ${input.value}`);
});
	
	//alert(ahora+"----"+futuro);	
	
	var Tabla1 = document.getElementById("tbdetalle");
	var ta=Tabla1.rows.length;
	
	var CeldasDeFila = Tabla1.rows[ta-1].getElementsByTagName('td');//datos necesarios para la tabla detalle salida	
	var vt=CeldasDeFila[6].textContent=puntosNumero(suma.toString());
	
 //alert("Ahora: "+ahora+" Futuro: "+futuro);
	
}






init();//ejecuta la función init