var tabla,tabla2;//variable global
//Función que se ejecuta al inicio

//var semiBar2 = new ProgressBar.SemiCircle("#semi-container2", {
  //color: "red",
  //strokeWidth: 5,
  //trailWidth: 10,
  //trailColor: "#5C5D5D",
  //easing: "bounce",
  //from: { color: "#FFFFFF", width: 5 },
  //to: { color: "#871F1C", width: 5 },
  //text: {
    //value: '0',
    //className: 'progress-text',
    //style: {
      //color: 'black',
      //position: 'absolute',
      //top: '45%',
      //left: '50%',
      //padding: 0,
      //margin: 0,
      //transform: null
    //}
  //},
  //step: (state, shape) => {
    //shape.path.setAttribute("stroke", state.color);
    //shape.path.setAttribute("stroke-width", state.width);
    //shape.setText(Math.round(shape.value() * 100) + ' %');
  //}
//});

var semiBar = new ProgressBar.SemiCircle("#semi-container", {
  color: "red",
  strokeWidth: 5,
  trailWidth: 10,
  trailColor: "#5C5D5D",
  easing: "bounce",
  from: { color: "#FFFFFF", width: 5 },
  to: { color: "#871F1C", width: 5 },
  text: {
    value: '0',
    className: 'progress-text',
    style: {
      color: 'black',
      position: 'absolute',
      top: '45%',
      left: '50%',
      padding: 0,
      margin: 0,
      transform: null
    }
  },
  step: (state, shape) => {
    shape.path.setAttribute("stroke", state.color);
    shape.path.setAttribute("stroke-width", state.width);
    shape.setText(Math.round(shape.value() * 100) + ' %');
  }
});

var semiBar3 = new ProgressBar.SemiCircle("#semi-container3", {
  color: "red",
  strokeWidth: 5,
  trailWidth: 10,
  trailColor: "#5C5D5D",
  easing: "bounce",
  from: { color: "#FFFFFF", width: 5 },
  to: { color: "#871F1C", width: 5 },
  text: {
    value: '0',
    className: 'progress-text',
    style: {
      color: 'black',
      position: 'absolute',
      top: '45%',
      left: '50%',
      padding: 0,
      margin: 0,
      transform: null
    }
  },
  step: (state, shape) => {
    shape.path.setAttribute("stroke", state.color);
    shape.path.setAttribute("stroke-width", state.width);
    shape.setText(Math.round(shape.value() * 100) + ' %');
  }
});

var semiBar4 = new ProgressBar.SemiCircle("#semi-container4", {
  color: "red",
  strokeWidth: 5,
  trailWidth: 10,
  trailColor: "#5C5D5D",
  easing: "bounce",
  from: { color: "#FFFFFF", width: 5 },
  to: { color: "#871F1C", width: 5 },
  text: {
    value: '0',
    className: 'progress-text',
    style: {
      color: 'black',
      position: 'absolute',
      top: '45%',
      left: '50%',
      padding: 0,
      margin: 0,
      transform: null
    }
  },
  step: (state, shape) => {
    shape.path.setAttribute("stroke", state.color);
    shape.path.setAttribute("stroke-width", state.width);
    shape.setText(Math.round(shape.value() * 100) + ' %');
  }
});

var barChartData = {
		labels : ["p1","p2"],
		datasets : [
			{
				fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                label: 'DISPONIBILIDAD VEHIULOS OPERATIVOS',
				data : [0,0],
			},
          
          
		]

	}	

var barChartData3 = {
		labels : ["p1","p2"],
		datasets : [
			{
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                label: 'DISPONIBILIDAD VEHIULOS ADMINISTRATIVOS',
				data : [0,0],
			},
          
		]

	}

var barChartDataiNO = {
		labels : ["p1"],
		datasets : [
			{
				fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                label: 'INOPERATIVIDAD VEHIULOS OPERATIVOS %',
				data : [0],
				barThickness: 100 
			},
          
          
		]

	}	


var barChartDataiNO2 = {
		labels : ["p1"],
		datasets : [
			{
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                label: 'INOPERATIVIDAD VEHIULOS ADMINISTRATIVOS %',
				data : [0],
				barThickness: 100 
			},
          
          
		]

	}	


function createChart() {
	
var barChartData2 = {
		labels : ["p1"],
		datasets : [
		      {
                fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                label: 'SINIESTRO',
				data : [0],
                
            },	
            {
                fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                label: 'CORRECTIVO',
				data : [0],
                
            },
			 {
                fillColor : "rgba(153,153,153,0.9)",
				strokeColor : "rgba(153,153,153,0.9)",
				highlightFill : "rgba(153,153,153,0.9)",
				highlightStroke : "rgba(153,153,153,1)",
                backgroundColor: "rgba(153,153,153,0.8)",
                label: 'PREVENTIVO',
				data : [0],
                
            },
		
		]

	}

return new Chart(ctx2, {
  type: 'bar',
  data: barChartData2,
  options: {
                scales: {
	   x: {
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      },	
      y: {
       beginAtZero: true,
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      }
    },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
					 var firstElement = elements[0];
                     var datasetIndex = firstElement.datasetIndex;
                     var novedad = myBar2.data.datasets[datasetIndex].label;
					 var proyecto = myBar2.data.labels[firstElement.index];	
                     handleDatasetClick(novedad,proyecto);	
						
        }
     }
 } 	
});  
	
}





 function handleDatasetClick(label1,label2) {
            //alert("Etiqueta seleccionada: " + label1+" proyecto"+label2);
	        //tabla.search(label).draw();
	        tabla.column(3).search(label1).draw();
	        tabla.column(2).search(label2).draw();
        }


var ctx = document.getElementById('canvas').getContext('2d');
var ctx3 = document.getElementById('canvas3').getContext('2d');
var ctx2 = document.getElementById('canvas2').getContext('2d');

var ctxINO = document.getElementById('canvasIN').getContext('2d');
var ctxINO2 = document.getElementById('canvasIN2').getContext('2d');
var myBar2 = createChart();  
var myBar = new Chart(ctx, {
  type: 'bar',
  data: barChartData,
   options: {
   scales: {
	   x: {
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      },	
      y: {
       beginAtZero: true,
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      }
    }
  },	
}); 

var myBar3 = new Chart(ctx3, {
  type: 'bar',
  data: barChartData3,
   options: {
    scales: {
	   x: {
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      },	
      y: {
       beginAtZero: true,
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      }
    }
  },	
}); 


var myBarINO = new Chart(ctxINO, {
  type: 'bar',
  data: barChartDataiNO,
   options: {
    scales: {
	   x: {
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      },	
      y: {
       beginAtZero: true,
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      }
    }
  },	
}); 

var myBarINO2 = new Chart(ctxINO2, {
  type: 'bar',
  data: barChartDataiNO2,
   options: {
   scales: {
	   x: {
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      },	
      y: {
       beginAtZero: true,
	   grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	  
      }
    }
  },	
}); 




function iniciarflod(){
var fechaActual = new Date();
const año = fechaActual.getFullYear(); // Obtiene el año
const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexados, por lo que sumamos 1 y aseguramos dos dígitos
const dia = String(fechaActual.getDate()).padStart(2, '0'); // Asegura dos dígitos	
const fechaFormateada = `${año}-${mes}-${dia}`;
fechaActual.setDate(fechaActual.getDate() - 1);
// Formatear la fecha en el formato deseado (por ejemplo, YYYY-MM-DD)
var año2 = fechaActual.getFullYear();
var mes2 = ('0' + (fechaActual.getMonth() + 1)).slice(-2); // Los meses son de 0 a 11
var dia2 = ('0' + fechaActual.getDate()).slice(-2);
var diapasado = `${año2}-${mes2}-${dia2}`;

	$("#fechainicio").val(diapasado);
	$("#fechafin").val(fechaFormateada);
	$('#proyecto2').val("0").trigger('change');	
	
}

function init()
{
	
	$("#grftotal").hide();

	 $.post("../Control/dasboradControl.php?op=select", function(data)
    {
     $("#proyecto").html(data);	
	 $("#proyecto2").html(data);
	 $("#proyecto3").html(data);	 
    });
	
	$.post("../Control/dasboradControl.php?op=select2", function(data)
    {
     $("#cliente").html(data);		 
    });
	
$("#proyecto").on('change', function () {
	var idproyecto=$("#proyecto").val();
	contrato(idproyecto);
    });	
	
$("#proyecto2").on('change', function () {
	var idproyecto=$("#proyecto2").val();
	var fechai=$("#fechainicio").val();
	var fechaf=$("#fechafin").val();
	if(idproyecto!="" && fechai!="" && fechaf!="" && fechaf>=fechai){	
	flota(idproyecto,fechai,fechaf);			
	}
    });	
	

	
$("#fechainicio").on('change', function () {
$('#proyecto2').val("").trigger('change');	
});	
	
$("#fechafin").on('change', function () {
$('#proyecto2').val("").trigger('change');	
});		
	
	$("#cliente").on('change', function () {
    var idcliente=$("#cliente").val();
	listarexperiencia(idcliente);	
    });		
	
$('#proyecto').select2({
    width: '100%' ,
    
});
	
$('#proyecto2').select2({
    width: '100%' ,
    
});	

$('#proyecto3').select2({
    width: '100%' ,
    
});		

$('#cliente').select2({
    width: '100%' ,
    
});	
	
var anota=2023;
	var contenidota='<option value="">SELECCIONE...</option>';
	for(i=1; i<=30;i++){
	anota=anota+1;		
	contenidota=contenidota+'<option value="'+anota+'">'+anota+'</option>';	
	}
	$("#ano").html(contenidota);
	$("#ano3").html(contenidota);
	
 $('#ano').select2({
    width: '100%' ,
    
});	

 $('#ano3').select2({
    width: '100%' ,
    
});		
	
$('#MES').select2({
    width: '100%' ,
    
});		
	
$('#peajes').hide();
$('#novedades').hide();	
$('#contratodash').hide();	
$('#operacion').hide();	
$('#finaciero').hide();		
$('#flotadash').hide();	
$('#experiencia').hide();
$('#combustible').hide();
$('#presupuesto').hide();
$('#disponibilidad').hide();
$('#DisponibilidadMes').hide();
$('#TI').hide();	
$("#fechafinN").val("");	
setTimeout(() => {	
var select = document.getElementById('proyecto');	
var optionToRemove = select.querySelector('option[value="0"]');	
select.removeChild(optionToRemove);	
flotacantidad();	
}, 1000);	  
		
}


function mostrar(op)
{
if(op==1){
$('#peajes').hide();
$('#novedades').show();	
$('#contratodash').hide();	
$('#operacion').hide();
$('#flotadash').hide();
$('#gestionH').hide();	
$('#finaciero').hide();
$('#TI').hide();	
iniciarNovedadN();	
}else if(op==2){
$('#peajes').hide();	
$('#novedades').hide();	
$('#contratodash').hide();
$('#flotadash').hide();
$('#gestionH').hide();
$('#operacion').show();	
$('#finaciero').hide();	
$('#TI').hide();	
}else if(op==3){
 $('#operacion').hide();		
 $('#novedades').hide();	
$('#contratodash').hide();
$('#flotadash').hide();	
$('#gestionH').hide();
$('#peajes').show();	
$('#finaciero').hide();	
$('#TI').hide();	
}else if(op==4){
$('#peajes').hide();
 $('#operacion').hide();		
 $('#novedades').hide();
 $('#flotadash').hide();		
$('#contratodash').show();
$('#finaciero').hide();
$('#gestionH').hide();	
$('#proyecto').val("1").trigger('change');	
$('#TI').hide();	
}else if(op==5){
$('#peajes').hide();
 $('#operacion').hide();		
 $('#novedades').hide();		
 $('#contratodash').hide();
 $('#finaciero').hide();
$('#gestionH').hide();
$('#flotadash').show();
$('#TI').hide();	
}else if(op==6){
$('#peajes').hide();
 $('#operacion').hide();		
 $('#novedades').hide();		
 $('#contratodash').hide();
 $('#flotadash').hide();
 $('#gestionH').hide();	
 $('#finaciero').show();
$('#TI').hide();	
}else if(op==7){
 $('#peajes').hide();
 $('#operacion').hide();		
 $('#novedades').hide();		
 $('#contratodash').hide();
 $('#flotadash').hide();
 $('#finaciero').hide();
 $('#gestionH').show();	
 $('#TI').hide();	
}else if(op==8){
 $('#peajes').hide();
 $('#operacion').hide();		
 $('#novedades').hide();		
 $('#contratodash').hide();
 $('#flotadash').hide();
 $('#finaciero').hide();
 $('#gestionH').hide();
 $('#gestionH').hide();		
 $('#TI').show();		
}
	

}



function mostrarcontrato(op)
{
if(op==1){
$('#experiencia').hide();
$('#ejecucion').show();	
}else if(op==2){
$('#experiencia').show();
$('#ejecucion').hide();	
listarexperiencia(0);	
}			

}


function mostrarflota(op)
{
if(op==1){
$('#combustible').hide();
$('#presupuesto').hide();
$('#listadoflota').hide();
$('#DisponibilidadMes').hide();		
$('#disponibilidad').show();
iniciarflod();	
}else if(op==2){
$('#disponibilidad').hide();
$('#presupuesto').hide();
$('#listadoflota').hide();
$('#DisponibilidadMes').hide();		
$('#combustible').show();		
iniciarCombustible();	
}else if(op==3){
$('#disponibilidad').hide();
$('#combustible').hide();
$('#listadoflota').hide();
$('#DisponibilidadMes').hide();		
$('#presupuesto').show();
}else if(op==4){
$('#disponibilidad').hide();
$('#combustible').hide();
$('#presupuesto').hide();
$('#DisponibilidadMes').hide();		
$('#listadoflota').show();
}else if(op==5){
$('#disponibilidad').hide();
$('#combustible').hide();
$('#presupuesto').hide();	
$('#listadoflota').hide();
$('#DisponibilidadMes').show();	
var fechaActual = new Date();
const año = fechaActual.getFullYear(); // Obtiene el año
$('#ano2').val(año).trigger('change');		
$('#proyecto3').val("0").trigger('change');	
}
	
	

}



function contrato(id)
{

$.post("../Control/dasboradControl.php?op=consulta",{proyecto:id}, function(data)
    {
	data = JSON.parse(data);
    $("#fechas").html(data.fechas);
	$("#contenido").html(data.contenido);
	var promedio=data.promedio.toFixed(2);
semiBar.animate(0.0, {
  duration: 500
 });	
 semiBar.animate(promedio, {
  duration: 2000
 });	
    });	
}


function flota(id,fechai,fechaf)
{

//if(id==0){
	//bootbox.dialog({
        //message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        //closeButton: false
        //});
//}	

$.post("../Control/dasboradControl.php?op=consulta2",{proyecto:id,finicio:fechai,ffinal:fechaf}, function(data)
    {
	if(id!=0){
	data = JSON.parse(data);
	$("#grftotal").hide();	
	var promedio=data.promediototal.toFixed(2);
    var promedio2=data.promedioO.toFixed(2);
    var promedio3=data.PromedioA.toFixed(2);
	var nombrespro=data.prtoyecton;
	var cantidadvh=data.cantidadvh;
	var cantidadano=data.cantidadno;
	var sinistro=data.siniestro;
	var corectivo=data.correctivo;
	var preventivo=data.preventivo;	
//semiBar2.animate(promedio, {
  //duration: 5000
 //});	
 semiBar3.animate(promedio2, {
  duration: 5000
 });
  semiBar4.animate(promedio3, {
  duration: 5000
 });
	 myBar2.destroy(); // Destruye la instancia actual de la gráfica	
	 myBar2 = createChart();	
	 myBar2.data.labels[0] =nombrespro;
	 //myBar2.data.datasets[0].data[0] =cantidadvh;
	 //myBar2.data.datasets[1].data[0] = cantidadano;
	 myBar2.data.datasets[0].data[0] = sinistro;
	 myBar2.data.datasets[1].data[0] = corectivo;
	 myBar2.data.datasets[2].data[0] = preventivo;		
     myBar2.update();			
 listar(data.listas,data.listprnovedades);	
	}else{		
	data = JSON.parse(data);
	$("#grftotal").show();	
	//alert(data.nombrep);	
	//alert(data.promediototal);
	//alert(data.promedioO);	
	//alert(data.PromedioA);	
	//alert(data.listatotal);
	//alert(data.listaop);	
	//alert(data.listaA);	
	//alert("pre:"+data.preventivo);
	//alert(data.cantidadvh);	
	//alert(data.cantidadno);		
	//alert(data.correctivo);
	//alert(data.siniestro);	
	var promedio=data.promediototal.toFixed(2);
    var promedio2=data.promedioO.toFixed(2);
    var promedio3=data.PromedioA.toFixed(2);
	var nombrespro=data.nombrep.split(";");
	var nombresproAdm=data.listaAnom.split(";");
	var nombresproOPR=data.listaOPnom.split(";");	
	var listatotales=data.listatotal.split(";");	
	var listaop=data.listaop.split(";");
	var listaadmin=data.listaA.split(";");
	var cantidadvh=data.cantidadvh.split(";");
	var cantidadano=data.cantidadno.split(";");
	var sinistro=data.siniestro.split(";");
	var corectivo=data.correctivo.split(";");
	var preventivo=data.preventivo.split(";");	
		
	var nombreopino=data.proyecyosinoOP.split(";");	
	var nombreadino=data.proyecyosinoAD.split(";");	
	var datosinoop=data.totalinoOP.split(";");	
	var datosinoad=data.totalinoAD.split(";");		
//semiBar2.animate(promedio, {
  //duration: 5000
 //});	
 semiBar3.animate(promedio2, {
  duration: 5000
 });
  semiBar4.animate(promedio3, {
  duration: 5000
 });
            myBar.data.labels =nombresproOPR;
            //myBar.data.datasets[0].data = listatotales;
            myBar.data.datasets[0].data = listaop;
            //myBar.data.datasets[2].data = listaadmin;
            myBar.update();	
		
		   myBar3.data.labels =nombresproAdm;
           //myBar.data.datasets[0].data = listatotales;
           //myBar.data.datasets[1].data = listaop;
           myBar3.data.datasets[0].data = listaadmin;
           myBar3.update();	
		
		   myBarINO.data.labels =nombreopino;
           myBarINO.data.datasets[0].data = datosinoop;
           myBarINO.update();	
		
		
		  myBarINO2.data.labels =nombreadino;
          myBarINO2.data.datasets[0].data = datosinoad;
          myBarINO2.update();	
		
		     myBar2.data.labels =nombrespro;
	         //myBar2.data.datasets[0].data =cantidadvh;
	         //myBar2.data.datasets[1].data = cantidadano;
	         myBar2.data.datasets[0].data = sinistro;
	         myBar2.data.datasets[1].data = corectivo;
	         myBar2.data.datasets[2].data = preventivo;		
             myBar2.update();				
 listar(data.listas,data.listprnovedades);	
 agregartablagn(data.nombrep,data.listatotal,data.inoperatividadG,data.backup,data.alquiler,data.redistribucion,data.norequiere,data.sinreemplazo);		
 //bootbox.hideAll();		
	}
		
    });	
}


function agregartablagn(proyectos,operatividad,inoperatividad,backup,alquiler,redistribucion,norrquiere,sinreemplazo)
{
 var proyectosg=proyectos.split(";");	
 var operatividadG=operatividad.split(";");	
 var inoperatividadG=inoperatividad.split(";");	
 var backupG=backup.split(";");	
 var alquilerG=alquiler.split(";");	
 var redistribucionG=redistribucion.split(";");	
 var norrquiereG=norrquiere.split(";");	
 var sinreemplazoG=sinreemplazo.split(";");
var contenido="";
for(i=0;i<proyectosg.length;i++) {
contenido=contenido+'<tr><td >'+proyectosg[i]+'</td><td style="font-size:12PX">'+operatividadG[i]+'%</td>'+'<td style="font-size:12PX">'+backupG[i]+'%</td>'+'<td style="font-size:12PX">'+alquilerG[i]+'%</td>'+'<td style="font-size:12PX">'+redistribucionG[i]+'%</td>'+'<td style="font-size:12PX">'+norrquiereG[i]+'%</td>'+'<td style="font-size:12PX">'+sinreemplazoG[i]+'%</td>'+'<td style="font-size:12PX">'+inoperatividadG[i]+'%</td>'+'</tr>';		
}
$("#datosgeneral").html(contenido);	
	
}


function listar(proyecto,nombrespr)
{
	//alert(nombrespr)
	
//alert(proyecto);	
//alert(proyecto+" "+fechai+" "+fechaf)	
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
                                    url: '../Control/dasboradControl.php?op=listar&proyecto='+proyecto+"&nombrespr="+nombrespr,//pagina que realiza la operación
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


function listarexperiencia(cliente)
{
	//alert(nombrespr)
	
//alert(proyecto);	
//alert(proyecto+" "+fechai+" "+fechaf)	
    tabla2=$('#tbcontratos').dataTable(//Carga variable con datos datatable
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
                                    url: '../Control/dasboradControl.php?op=experiencia&clienteb='+cliente,//pagina que realiza la operación
                                    type : "get",//tipo de envio de datos
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[ 5, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
    
}

function flotacantidad()
{
	$.post("../Control/dasboradControl.php?op=listadoflota", function(data){
	var datos = JSON.parse(data);
	     var contenido="";
		 datos.forEach(function(data) {
		 contenido=contenido+'<tr><td >'+data.nombreProyecto+'</td><td style="font-size:12PX">'+data.administrativo+'</td>'+'<td style="font-size:12PX">'+data.operativo+'</td>'+'<td style="font-size:12PX">'+data.CANTIDAD+'</td>'+'</tr>';	 
		 });
		 $("#contenflota").html(contenido);	
		 listarFlota();
    });	

}



function listarFlota()
{
	
    $('#tbvehiculos').dataTable(//Carga variable con datos datatable
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
                                    url: '../Control/dasboradControl.php?op=listarvhflota',//pagina que realiza la operación
                                    type : "get",//tipo de envio de datos
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[ 2, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
    
}


function mostrarcontenido(idnovedad)
{ 
	$.post("../Control/NovedadFlotaControl.php?op=mostrarNovedad",{idnovedad : idnovedad}, function(data)
    {  
	$("#contenidoNota").html(data)
    
    });
}


function objetocontrato(novedad){
      bootbox.alert({
                        title: 'OBJETO CONTRATO!',
                        message: novedad,
                        size: 'small',
        });              
}

function mostrarpanel(panel) {
  const panels = document.getElementById(panel);
  if (panels.classList.contains('active')) {
    panels.classList.remove('active');
  } else {
    panels.classList.toggle('active');
  }
}


init();//ejecuta la función init