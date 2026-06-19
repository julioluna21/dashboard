


var barChartDataT1 = {
        labels: ['N/D'],
        datasets: [
            {
                label: 'PMF',
				fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                data: [0]
                
            },
            {
                label: 'GMF',
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                data: [0]
            }
        ]
    }	

  /*var barChartDataC2 = {
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

    });	*/





var ctxT1 = document.getElementById('canvasTiGastos').getContext('2d');
//var ctxC2 = document.getElementById('canvasC2').getContext('2d');
//var ctxC3 = document.getElementById('canvasC3').getContext('2d');

var myBarT1 = new Chart(ctxT1, {
  type: 'bar',
  data: barChartDataT1,
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


/*var myBarC2 = new Chart(ctxC2, {
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
}); */


function iniciarTI(){
let hoy = new Date();
// Restar un mes
let mesAnterior = hoy.getMonth() - 1;
let año = hoy.getFullYear();
$('#ano3').val(año).trigger('change');
//$('#MES').val(nombreMesAnterior).trigger('change');	
}

function GestionGastoTI(){
//var mes=$('#MES').val();
var ano=$('#ano3').val();	
if(ano!=""){
$.post("../Control/dasboradControl.php?op=TI",{ano:ano}, function(data)
    {
	//console.log(data);
	data = JSON.parse(data);
	var meses=data.meses.split(";");
	var Presupuesto=data.Presupuesto.split(";");
    var Gestion=data.Gestion.split(";");
    	
    

           myBarT1.data.labels =meses;
           myBarT1.data.datasets[0].data = Presupuesto;
	       myBarT1.data.datasets[1].data = Gestion;
           myBarT1.update();	
		
    });	

}	

}


$("#ano3").on('change', function () {
	GestionGastoTI();
});	

iniciarTI();	

	 

