var barChartDataD1 = {
		labels : ["p1"],
		datasets : [
			{
				fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                label: 'DISPONIBILIDAD OPERATIVA',
				data : [0],
			},
          
          
		]

	}	

var barChartDataD2 = {
		labels : ["p1"],
		datasets : [
			{
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                label: 'DISPONIBILIDAD ADMINISTRATIVA',
				data : [0],
			},
          
          
		]

	}



var ctxD1 = document.getElementById('canvasD1').getContext('2d');
var ctxD2 = document.getElementById('canvasD2').getContext('2d');

var myBarD1 = new Chart(ctxD1, {
  type: 'bar',
  data: barChartDataD1,
   options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },	
}); 

var myBarD2 = new Chart(ctxD2, {
  type: 'bar',
  data: barChartDataD2,
   options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },	
}); 
 
function iniciarDisMes(){
let hoy = new Date();
let año = hoy.getFullYear();
	
}

function flotaMes(id,anio)
{

//if(id==0){
	//bootbox.dialog({
        //message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Consultando la base de datos...</div>',
        //closeButton: false
        //});
//}	
//bootbox.hideAll();	

$.post("../Control/dasboradControl.php?op=consultaMes",{proyecto:id,ano:anio}, function(data)
    {
	data = JSON.parse(data);
	//alert(data.meses);
	//alert(data.promedioO);
	//alert(data.PromedioA);
	var nombresmese=data.meses.split(";");
	var promediosOP=data.promedioO.split(";");
	var promediosAD=data.PromedioA.split(";");
    myBarD1.data.labels =nombresmese;
	myBarD1.data.datasets[0].data = promediosOP;	
    myBarD1.update();	
		
	 myBarD2.data.labels =nombresmese;
	 myBarD2.data.datasets[0].data = promediosAD;
     myBarD2.update();		
				
		
    });	
}



function inicio2(){
	
	
var anota=2023;
	var contenidota='<option value="">SELECCIONE...</option>';
	for(i=1; i<=30;i++){
	anota=anota+1;		
	contenidota=contenidota+'<option value="'+anota+'">'+anota+'</option>';	
	}
	$("#ano2").html(contenidota);		
	
 $('#ano2').select2({
    width: '100%' ,
    
});		


$("#proyecto3").on('change', function(){
	var idproyecto=$("#proyecto3").val();
	var anio=$("#ano2").val();
	var fechaf=$("#fechafin").val();
	if(idproyecto!="" && anio!=""){
	flotaMes(idproyecto,anio);			
	}
  });		
	
	
}

inicio2();

	


