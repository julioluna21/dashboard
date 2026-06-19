var tabla,estado,fecha="",centro="",opciones="";//variable global 
const searchInput = document.getElementById("Placa");
const searchResults = document.getElementById("searchResults");
  const campos = [
        "HoraReporte",
        "HoraLLegada",
        "HorainicioT",
        "HoraFinT",
        "Horafinservico",
        "Horabase"
    ];
//Función que se ejecuta al inicio
function init()
{
    mostrarform(false,false);
	estado=1;
    listar();//lista 
    $("#General").hide();
    $("#ambulancia").hide();
//al oprimir el boton del formulario
    $("#formregistros").on("submit",function(e)//e = variable que contiene el objeto
    {
            guardar(e);//guarda o edita el articulo
    });
	

    $("#Servicio").on("change",function(e)//e = variable que contiene el objeto
    {

    $("#Pheridas").val("");
        $("#Hgraves").val("");
        $("#Hleves").val("");
        $("#Hilesos").val("");
        $("#Fallecidos").val(""); 
        $("#prtraslado").val("");
        $("#rutaTraslado").val("");
        $("#TipoVehiculoAtencion").val("");
        $("#CategoriaVehiculo").val("");   
    const tipo=$("#Servicio").val();
    $("#General").show();
    if(tipo==1 || tipo==4){
        $("#tipo1").show();   
        $("#tipo2").show(); 
        $("#tipo3").hide();  
        $("#Pheridas").val(0);
        $("#Hgraves").val(0);
        $("#Hleves").val(0);
        $("#Hilesos").val(0);
        $("#Fallecidos").val(0);
    }else if(tipo==5){
        $("#prtraslado").val(0);
        $("#rutaTraslado").val(0);
        $("#TipoVehiculoAtencion").val("N/A");
        $("#CategoriaVehiculo").val("N/A");
        $("#tipo1").hide();
        $("#tipo2").hide();
        $("#tipo3").show();   
    }else{
        $("#Pheridas").val(0);
        $("#Hgraves").val(0);
        $("#Hleves").val(0);
        $("#Hilesos").val(0);
        $("#Fallecidos").val(0);
        $("#prtraslado").val(0);
        $("#rutaTraslado").val(0);
        $("#tipo2").show();
        $("#tipo1").hide();
        $("#tipo3").hide();   
    }
       
    });


    $.post("../Control/VehiculoControl.php?op=select",function(data)
    {
     $("#TipoVehiculo").html(data);
    });	
			
	 $.post("../Control/DatosAsistencialesControl.php?op=select1", function(data)
    {
     $("#centro").html(data);
     $("#centrobus").html(data);
    });

    $.post("../Control/DatosAsistencialesControl.php?op=select2", function(data)
    {
    data = JSON.parse(data);
    opciones=data.datos;
    });

    $.post("../Control/DatosAsistencialesControl.php?op=select3", function(data)
    {
     $("#TipoEvento").html(data);
    });
		 

 	$('#centro').select2({
    width: '100%' ,
    
});

$('#centrobus').select2({
    width: '100%' ,
    
});
	$('#TipoVehiculo').select2({
    width: '100%' ,
    
});	


 	$('#Servicio').select2({
    width: '100%' ,
    
});
	
 	$('#TipoVehiculoAtencion').select2({
    width: '100%' ,
    
});	

$('#CategoriaVehiculo').select2({
    width: '100%' ,
});	

$('#TipoEvento').select2({
    width: '100%' ,
});	
	
searchInput.addEventListener("input", () => {
  const query = searchInput.value.toLowerCase().trim();
  searchResults.innerHTML = "";

  if (query.length === 0) {
    searchResults.style.display = "none";
    return;
  }

  const resultadosFiltrados = opciones.filter(op =>
    op.nombre.toLowerCase().includes(query)
  );

  if (resultadosFiltrados.length === 0) {
    searchResults.innerHTML = "<div class='search-item'>No se encontraron resultados</div>";
  } else {
    resultadosFiltrados.forEach(op => {
      const div = document.createElement("div");
      div.classList.add("search-item");
      div.innerHTML = `${op.icono}<span>${op.nombre}</span>`;
      div.addEventListener("click", () => {
        $("#Placa").val(op.url);
      });
      searchResults.appendChild(div);
    });
  }

  searchResults.style.display = "block";
});

document.addEventListener("click", e => {
  if (!e.target.closest(".search-container")) {
    searchResults.style.display = "none";
  }
});

 campos.forEach((id, index) => {
        const input = document.getElementById(id);
        input.addEventListener("change", function () {
            validarOrden(index);
        });
    });


}

function validarOrden(index) {
        if (index === 0) return; // El primero no se compara con nada

        const anteriorId = campos[index - 1];
        const actualId = campos[index];

        const anterior = document.getElementById(anteriorId).value;
        const actual = document.getElementById(actualId).value;

        if (anterior && actual) {
            // Convertir a fecha para comparar
            const fechaAnterior = new Date(anterior);
            const fechaActual = new Date(actual);

            if (fechaActual < fechaAnterior) {
                alert(
                    "La fecha/hora seleccionada no es válida.\n" +
                    "Debe ser mayor que la anterior"
                );

                document.getElementById(actualId).value = ""; // Limpia el campo incorrecto
            }
        }
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
    mostrarform(false,false);
}


//Función limpiar, pone el formulario en blanco
function limpiar()
{
    document.getElementById("formregistros").reset();
	$('#idCargue').val("");
	$('#centro').val("").trigger('change');
	$('#TipoVehiculo').val("").trigger('change');
    $('#Servicio').val("").trigger('change');
    $('#TipoVehiculoAtencion').val("").trigger('change');
    $('#CategoriaVehiculo').val("").trigger('change');
    $('#TipoEvento').val("").trigger('change');
	$("#fecha").prop("disabled",false);
	$("#centro").prop("disabled",false);
    $("#General").hide();
    
	fecha="";
	centro="";
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
	$("#btnGuardar").prop("disabled",true);
    $("#fechaservicio, #centro, #TipoEvento, #Pr, #Metros, #Uf, #TipoVehiculo, #Placa, #Prsalida, #RnSalida, #Servicio, #prtraslado, #rutaTraslado, #TipoVehiculoAtencion, #CategoriaVehiculo, #Pheridas, #Hgraves, #Hleves, #Hilesos, #Fallecidos, #HoraReporte, #HoraLLegada")
  .prop("disabled", false);
    var formData = new FormData($("#formregistros")[0]);
    $.ajax({
        url: "../Control/DatosAsistencialesControl.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
          bootbox.alert(datos);
          //console.log(datos); 
          mostrarform(false);
          tabla.ajax.reload();    
          limpiar();   
            
        }
    });
    limpiar();	
    
}


function mostrar2(id)
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
    const general=data.general;
    const detalle=data.Detalle;
    mostrarform(true)       
    $("#idCargue").val(general.ID_SERVICIO_ASISTENCIAL);
    $("#fechaservicio").val(general.FECHA_SERVICIO); 
    $('#centro').val(general.CENTRO_SERVICIO).trigger('change');	
	$('#TipoEvento').val(general.TIPO_EVENTO_SERVICIO).trigger('change');		 
	$("#Pr").val(general.PR); 		 
	$("#Metros").val(general.METROS); 
	$("#Uf").val(general.UF);
    $('#TipoVehiculo').val(general.TIPO_VEHICULO_SERVICIO).trigger('change');
    $("#Placa").val(general.PLACA_VEHICULO);
    $("#Prsalida").val(general.PR_SALIDA);
    $("#RnSalida").val(general.RN_SALIDA);
    $('#Servicio').val(general.DATOS_SERVICIO).trigger('change');

    if(general.DATOS_SERVICIO!="5"){
    $("#prtraslado").val(detalle.PR_TRASLADO); 
	$("#rutaTraslado").val(detalle.RUTA_TRASLADO); 
    $('#TipoVehiculoAtencion').val(detalle.TIPO_VEHICULO_ATENDIDO).trigger('change');
    $('#CategoriaVehiculo').val(detalle.CATEGORIA).trigger('change');
    }else{
    $("#Pheridas").val(detalle.NUM_PERSONA_ATENDIDAS); 
    $("#Hgraves").val(detalle.HERIDOS_GRAVES); 
    $("#Hleves").val(detalle.HERIDOS_LEVES); 
    $("#Hilesos").val(detalle.HERIDOS_ILESOS); 
    $("#Fallecidos").val(detalle.FALLECIDOS);    
    }
    $("#HoraReporte").val(detalle.HORA_REPORTE); 
    $("#HoraLLegada").val(detalle.HORA_LLEGADA); 
    $("#HorainicioT").val(detalle.HORA_INICIO_TRASLADO); 
    $("#HoraFinT").val(detalle.HORA_FIN_TRASLADO); 
    $("#Horafinservico").val(detalle.HORA_FIN_SERVICIO); 
    $("#Horabase").val(detalle.HORA_BASE);    
    
    $("#fechaservicio, #centro, #TipoEvento, #Pr, #Metros, #Uf, #TipoVehiculo, #Placa, #Prsalida, #RnSalida, #Servicio, #prtraslado, #rutaTraslado, #TipoVehiculoAtencion, #CategoriaVehiculo, #Pheridas, #Hgraves, #Hleves, #Hilesos, #Fallecidos, #HoraReporte, #HoraLLegada")
  .prop("disabled", true);
             		 
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


function Reporte(){
    var centro=$("#centrobus").val();
    var fechai=$("#fechainical").val();
    var fechaf=$("#fechafinal").val();

    if(centro!="" && fechai!="" && fechaf!=""){
    $.post("../Control/DatosAsistencialesControl.php?op=Reporte&centrobus="+centro+"&fechainicial="+fechai+"&fechafinal="+fechaf, function(data)
    {
       data = JSON.parse(data);
       // Crear hoja de trabajo
       const wb = XLSX.utils.book_new();
       const ws1 = XLSX.utils.aoa_to_sheet(data.grua);
       const ws2 = XLSX.utils.aoa_to_sheet(data.carroTaller);
       const ws3 = XLSX.utils.aoa_to_sheet(data.inspectorvial);
       const ws4 = XLSX.utils.aoa_to_sheet(data.accidente);
       const ws5 = XLSX.utils.aoa_to_sheet(data.ambulancia);

       XLSX.utils.book_append_sheet(wb, ws1, "Gruas");
       XLSX.utils.book_append_sheet(wb, ws2, "Carros Taller");
       XLSX.utils.book_append_sheet(wb, ws3, "Inspector Vial");
       XLSX.utils.book_append_sheet(wb, ws4, "Accidentes");
       XLSX.utils.book_append_sheet(wb, ws5, "Ambulancias"); 
       // Descargar Excel
       XLSX.writeFile(wb, "Reporte_Multiple.xlsx");

    });

    }else{
         bootbox.alert("Debe seleccionar el centro y el rango de fechas para generar el reporte");
    }
   
}



init();//ejecuta la función init