
//Función que se ejecuta al inicio
function init()
{
    $.post("../Control/ReporteAsistencialControl.php?op=SelectProyecto",function(data)
    {
     $("#proyecto").html(data);
    });	

 	$('#proyecto').select2({
    width: '100%' , 
});


}



function Reporte(){
    var proyecto = $('#proyecto').val();
    var fechaInicio = $('#fechaInicio').val();
    var fechaFin = $('#fechaFin').val();

    if(proyecto == "" || fechaInicio == "" || fechaFin == ""){
        alert("Por favor, complete todos los campos para generar el reporte.");
        return;
     }  
    
    $.ajax({
    url: "../Control/ReporteAsistencialControl.php?op=Reporte",
    type: "POST",
    data: { proyecto: proyecto, fechaInicio: fechaInicio, fechaFin: fechaFin },
    success: function(data)
    {
    data = JSON.parse(data);
    // Crear hoja de trabajo
       const wb = XLSX.utils.book_new();
       const ws1 = XLSX.utils.aoa_to_sheet(data.general);
       XLSX.utils.book_append_sheet(wb, ws1, "General");
       // Descargar Excel
       XLSX.writeFile(wb, "Reporte_Multiple.xlsx");
           
            
    }
    });
   
}

init();//ejecuta la función init