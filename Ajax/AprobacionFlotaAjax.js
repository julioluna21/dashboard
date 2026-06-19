var tabla;//variable global
function init()
{
   //listar(3,0);//lista 
}




function listar(estado,estado2)
{
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
                                url: '../Control/AprobacionPControl.php?op=listar',//pagina que realiza la operación
                                type : "POST",//tipo de envio de datos
								data: {estado1:estado,estado2:estado2},
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


function aprobacion(id,estado)
{
	 bootbox.confirm({
            message: "Desea aprobar este registro?",
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
                
	$.post("../Control/AprobacionPControl.php?op=Aprobar",{idPresupuesto : id,"tipo":estado}, function(data)
    {
    bootbox.alert(data);
	tabla.ajax.reload();	
    });			
					
                }
            }
        });

}



init();//ejecuta la función init