var barChartDataC1 = {
		labels : ["p1","p2"],
		datasets : [
			{
				fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                label: 'COMBUSTIBLE',
				data : [0,0],
			},
          
          
		]

	}	

var barChartDataC2 = {
		labels : ["p1","p2"],
		datasets : [
			{
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                label: 'KILOMETROS',
				data : [0,0],
			},
          
          
		]

	}

var barChartDataC3 = {
		labels : ["p1","p2"],
		datasets : [
			{
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,0.5)",
                label: 'SERVICIOS ATENDIDOS',
				data : [0,0],
			},
          
          
		]

	}

var ctxC1 = document.getElementById('canvasC1').getContext('2d');
var ctxC2 = document.getElementById('canvasC2').getContext('2d');
var ctxC3 = document.getElementById('canvasC3').getContext('2d');

var myBarC1 = new Chart(ctxC1, {
  type: 'bar',
  data: barChartDataC1,
   options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },	
}); 

var myBarC2 = new Chart(ctxC2, {
  type: 'bar',
  data: barChartDataC2,
   options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },	
}); 

var myBarC3 = new Chart(ctxC3, {
  type: 'bar',
  data: barChartDataC3,
   options: {
    scales: {
      y: {
        beginAtZero: true
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
if (mesAnterior < 0) {
  mesAnterior = 11; // Diciembre
  año -= 1;
}
// Obtener el nombre del mes anterior
const nombresMeses = [
  "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO",
  "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
];
let nombreMesAnterior = nombresMeses[mesAnterior];	
	
$('#ano').val(año).trigger('change');
$('#MES').val(nombreMesAnterior).trigger('change');
	
}

function CombustibleGR(){
var mes=$('#MES').val();
var ano=$('#ano').val();	
if(mes!="" && ano!=""){
$.post("../Control/dasboradControl.php?op=Combustible",{MES:mes,ano:ano}, function(data)
    {
	data = JSON.parse(data);
	$("#grftotal").show();	
	var proyectos=data.proyectos.split(";");
    var combustible=data.combustible.split(";");
    var kilometros=data.kilometros.split(";");
	var servicios=data.servicios.split(";");
		


           myBarC1.data.labels =proyectos;
           myBarC1.data.datasets[0].data = combustible;
           myBarC1.update();	
		
		   myBarC2.data.labels =proyectos;
           myBarC2.data.datasets[0].data = kilometros;
           myBarC2.update();	
	
	       myBarC3.data.labels =proyectos;
           myBarC3.data.datasets[0].data = servicios;
           myBarC3.update();			
		
    });		
}	

}

$("#MES").on('change', function () {
	CombustibleGR();
});	
	


