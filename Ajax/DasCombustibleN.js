
var tamano=0,tamano2=0, tablaCombustible;

var colores = [
    "rgba(135,31,27,1)",    // Rojo oscuro
    "rgba(190,47,42,1)",    // Rojo brillante
    "rgba(240,128,128,1)",  // Rojo salmón claro
    "rgba(255,99,71,1)",    // Rojo tomate
    "rgba(205,92,92,1)",    // Rojo indio
    "rgba(92,93,93,1)",     // Gris oscuro
    "rgba(150,150,150,1)",  // Gris medio
    "rgba(211,211,211,1)",  // Gris claro
    "rgba(169,169,169,1)",  // Gris oscuro claro
    "rgba(192,192,192,1)",  // Gris plata
    "rgba(200,40,35,1)",    // Rojo cálido
    "rgba(245,80,75,1)",    // Rojo vivo
    "rgba(255,69,0,1)",     // Rojo anaranjado
    "rgba(220,20,60,1)",    // Carmesí
    "rgba(165,42,42,1)",    // Marrón rojizo
    "rgba(110,110,110,1)",  // Gris mediano oscuro
    "rgba(140,40,35,1)",    // Rojo ladrillo
    "rgba(255,160,122,1)",  // Salmón claro
    "rgba(75,75,75,1)",     // Gris carbón
    "rgba(250,128,114,1)"   // Salmón
];


var barChartDataC1 = {
        labels: ['N/D'],
        datasets: [
            {
                label: 'RENDIMIENTO',
				fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                data: [0]
                
            },
            {
                label: 'SERVICIOS ATENDIDOS',
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                data: [0]
            },
            {
                label: 'KILOMETROS RECORRIDOS',
				fillColor : "rgba(153,153,153,0.9)",
				strokeColor : "rgba(153,153,153,0.9)",
				highlightFill : "rgba(153,153,153,0.9)",
				highlightStroke : "rgba(153,153,153,1)",
                backgroundColor: "rgba(153,153,153,0.8)",
                data: [0]
            }
        ]
    }	

  var barChartDataC2 = {
            labels: ["N/D"],
            datasets: []
        }
  
  var barChartDataC3 = {
		labels : ["N/D"],
		datasets : []

	}
  
  $.post("../Control/dasboradControl.php?op=listarTipoVehiculo",function(data){
		
	data = JSON.parse(data);
	var tipo=data.Tipovehiculos.split(";");	
	for (var i = 0; i < tipo.length;i++) {
                var newDataset = {
					label: tipo[i],
                    fillColor: colores[i],
                    strokeColor: colores[i],
                    highlightFill: colores[i],
                    highlightStroke: colores[i],
                    backgroundColor: colores[i],
                    data: [0],
                };
                barChartDataC2.datasets.push(newDataset);
            }		

    });	

 $.post("../Control/dasboradControl.php?op=listarProyectosCombustible",function(data){
	data = JSON.parse(data);
	var Proyecto=data.NombreProyecto.split(";");	
	for (var i = 0; i < Proyecto.length;i++) {
                var newDataset = {
					label: Proyecto[i],
                    fillColor: colores[i],
                    strokeColor: colores[i],
                    highlightFill: colores[i],
                    highlightStroke: colores[i],
                    backgroundColor: colores[i],
                    data: [0],
                };
                barChartDataC3.datasets.push(newDataset);
            }		

    });	





var ctxC1 = document.getElementById('canvasC1').getContext('2d');
var ctxC2 = document.getElementById('canvasC2').getContext('2d');
var ctxC3 = document.getElementById('canvasC3').getContext('2d');

var myBarC1 = new Chart(ctxC1, {
  type: 'bar',
  data: barChartDataC1,
   options: {
        scales: {
            x: {
				beginAtZero: true,
				grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	
            },
            y: {
				type: 'logarithmic',
				beginAtZero: true,
				grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	
            }
        }
    },	
}); 


var myBarC2 = new Chart(ctxC2, {
  type: 'bar',
  data: barChartDataC2,
   options: {
    scales: {
            x: {
                stacked: true, // Hace que las barras se apilen en el eje X
				beginAtZero: true,
				grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	
            },
            y: {
                stacked: true, // Hace que las barras se apilen en el eje Y
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
                     var tipo = myBarC2.data.datasets[datasetIndex].label;
					 var mes = myBarC2.data.labels[firstElement.index];	
                     handleDatasetClickCombustible(tipo,mes);	
						
        }
     }
  },	
}); 	



var myBarC3 = new Chart(ctxC3, {
  type: 'bar',
  data: barChartDataC3,
   options: {
     scales: {
            x: {
                stacked: true, // Hace que las barras se apilen en el eje X
				beginAtZero: true,
				grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	
            },
            y: {
                stacked: true, // Hace que las barras se apilen en el eje Y
				beginAtZero: true,
				grid: {
                    display: false // Oculta la cuadrícula del eje Y
                }	
            }
        }
  },	
}); 


function iniciarCombustible(){
let hoy = new Date();
// Restar un mes
let mesAnterior = hoy.getMonth() - 1;
let año = hoy.getFullYear();
// Ajustar si el mes es enero
/*if (mesAnterior < 0) {
  mesAnterior = 11; // Diciembre
  año -= 1;
}*/
// Obtener el nombre del mes anterior
const nombresMeses = [
  "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO",
  "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
];
let nombreMesAnterior = nombresMeses[mesAnterior];	
$('#ano').val(año).trigger('change');
//$('#MES').val(nombreMesAnterior).trigger('change');	
}

function CombustibleGR(){
//var mes=$('#MES').val();
var ano=$('#ano').val();	
if(ano!=""){
$.post("../Control/dasboradControl.php?op=Combustible",{ano:ano}, function(data)
    {
	console.log(data);
	data = JSON.parse(data);
	var meses=data.meses.split(";");
	var kilometros=data.kilometros.split(";");
    var servicios=data.servicios.split(";");
    var rendimiento=data.rendimiento.split(";");
	var tipoveh=data.tipovehiculo;
	var proyectosR=data.Proyectos;
    var keys = Object.keys(data.tipovehiculo);	
	var keys2 = Object.keys(data.Proyectos);	
    

           myBarC1.data.labels =meses;
           myBarC1.data.datasets[0].data = rendimiento;
	       myBarC1.data.datasets[1].data = servicios;
	       myBarC1.data.datasets[2].data = kilometros;
           myBarC1.update();	
	       myBarC2.data.labels =meses;
	       myBarC3.data.labels =meses;
	
	       for(var i=0;i<tamano;i++){
		    myBarC2.data.datasets[i].data =0;   
		   }
	       for(var i=0;i<tamano2;i++){
		    myBarC3.data.datasets[i].data =0;   
		   }
	
	       for (var i = 0; i < keys.length;i++) {
		   var tipo = keys[i]; // Obtener la clave actual	   
		   datos=tipoveh[tipo].split(";");	   	   	   
           myBarC2.data.datasets[i].data =datos;	   
		   }
	      
	       for (var i = 0; i < keys2.length;i++) {
		   var tipo = keys2[i]; // Obtener la clave actual	   
		   datos=proyectosR[tipo].split(";");	   	   	   
           myBarC3.data.datasets[i].data =datos;	   
		   }
	 
	       myBarC2.update();
		   tamano=keys.length;
	       myBarC3.update();
		   tamano2=keys2.length;
	
	       listarFlotaCombustible(ano);		
		
    });	

}	

}

function listarFlotaCombustible(anio)
{

    tablaCombustible=$('#tbcombustible').dataTable(//Carga variable con datos datatable
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
                                    url: '../Control/dasboradControl.php?op=listadoflotaCombustible&anio='+anio,//pagina que realiza la operación
                                    type : "get",//tipo de envio de datos
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[ 11, "DESC" ]]//Ordenar (columna,orden)
    }).DataTable();
    
}

$("#ano").on('change', function () {
	CombustibleGR();
});	


 function handleDatasetClickCombustible(label1,label2) {
            //alert("Etiqueta seleccionada: " + label1+" proyecto"+label2);
	        //tabla.search(label).draw();
	        tablaCombustible.column(1).search(label1).draw();
	        tablaCombustible.column(3).search(label2).draw();
        }
	

	 

