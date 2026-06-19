var tablaN;

function crearcanNovedades(){
var barChartDataN1 = {
		labels : ["p1"],
		datasets : [
			{
				fillColor : "rgba(135,31,27,0.9)",
				strokeColor : "rgba(135,31,27,0.9)",
				highlightFill : "rgba(135,31,27,0.9)",
				highlightStroke : "rgba(135,31,27,1)",
                backgroundColor: "rgba(135,31,27,1)",
                label: 'ABIERTA',
				data : [0],
				
			},
			{
				fillColor : "rgba(92,93,93,0.9)",
				strokeColor : "rgba(92,93,93,0.9)",
				highlightFill : "rgba(92,93,93,0.9)",
				highlightStroke : "rgba(92,93,93,1)",
                backgroundColor: "rgba(92,93,93,1)",
                label: 'CERRADA',
				data : [0],
				
			},
          
          
		]

	}	


return new Chart(ctxD1, {
  type: 'bar',
  data: barChartDataN1,
   options: {
    scales: {
      y: {
        beginAtZero: true
      }
    },
	onClick: (event, elements) => {
                    if (elements.length > 0) {
					 var firstElement = elements[0];
                     var datasetIndex = firstElement.datasetIndex;
                     var novedad = myBarN1.data.datasets[datasetIndex].label;
					 var proyecto = myBarN1.data.labels[firstElement.index];	
                     BucaNovedad(novedad,proyecto);	
						
        }
     }   
  }	
}); 


}





var ctxD1 = document.getElementById('canvasNOVEDADES').getContext('2d');


function BucaNovedad(label1,label2) {
            //alert("Etiqueta seleccionada: " + label1+" proyecto"+label2);
	        //tabla.search(label).draw();
	        tablaN.column(1).search(label2).draw();
	        tablaN.column(5).search(label1).draw();
        }

var myBarN1 = crearcanNovedades(); 

function iniciarnovedades(fechai,fechaf)
{
$.post("../Control/dasboradControl.php?op=NovedadesN",{finicio:fechai,ffinal:fechaf}, function(data)
    {
	//alert("entro");
	data = JSON.parse(data);
	var nombresP=data.proyectos.split(";");
	var abiertas=data.Abiertas.split(";");
	var cerradas=data.Cerradas.split(";");
	
	myBarN1.destroy(); // Destruye la instancia actual de la gráfica	
	myBarN1 = crearcanNovedades();
    myBarN1.data.labels=nombresP;
	
	myBarN1.data.datasets[0].data = abiertas;
	myBarN1.data.datasets[1].data = cerradas;
    myBarN1.update();
    
	var numberOfBars = myBarN1.data.datasets[0].data.length;
    if (numberOfBars ==1) {
                 myBarN1.data.datasets[0].barThickness = 100;
            } 
	
	
    listarNovedadesN(fechai,fechaf);	
				
		
    });	
}


function listarNovedadesN(fechai,fechaf)
{
	//alert(nombrespr)
	
//alert(proyecto);	
//alert(proyecto+" "+fechai+" "+fechaf)	
    tablaN=$('#tbNovedades').dataTable(//Carga variable con datos datatable
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
                                    url: '../Control/dasboradControl.php?op=ListarNovedadesN&finicio='+fechai+"&ffinal="+fechaf,//pagina que realiza la operación
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



function inicio3(){
	
$("#fechainicioN").on('change', function(){
$("#fechafinN").val("");	
});
	
$("#fechafinN").on('change', function(){
//alert("entro1");	
var fechai=$("#fechainicioN").val();
var fechaf=$("#fechafinN").val();
	
if(fechai!="" && fechaf!=""){
//alert("entro2");	
iniciarnovedades(fechai,fechaf);		
}	
});	
	
	
}

function iniciarNovedadN(){
var fechaActual = new Date();
const año = fechaActual.getFullYear(); // Obtiene el año
const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexados, por lo que sumamos 1 y aseguramos dos dígitos
const dia = String(fechaActual.getDate()).padStart(2, '0'); // Asegura dos dígitos	
const fechaFormateada = `${año}-${mes}-${dia}`;
fechaActual.setDate(fechaActual.getDate() - 30);
// Formatear la fecha en el formato deseado (por ejemplo, YYYY-MM-DD)
var año2 = fechaActual.getFullYear();
var mes2 = ('0' + (fechaActual.getMonth() + 1)).slice(-2); // Los meses son de 0 a 11
var dia2 = ('0' + fechaActual.getDate()).slice(-2);
var diapasado = `${año2}-${mes2}-${dia2}`;	
	$("#fechainicioN").val(diapasado);
	$("#fechafinN").val(fechaFormateada);
    iniciarnovedades(diapasado,fechaFormateada);
	
}


function mostrarcontenidoNovedades(idnovedad)
{  
	$.post("../Control/NovedadesControl.php?op=mostrarNovedad",{idnovedad : idnovedad}, function(data)
    {  
	$("#contenidoNovedades").html(data);
    });
}

inicio3();



	