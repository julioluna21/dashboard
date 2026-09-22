var tabla;

function init()
{
    mostrarform(false);
    listar(1);

    $("#formregistros").on("submit",function(e)
    {
            guardar(e);
    });
}

function mostrarform(flag)
{
    if (flag)
    {
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

function cancelarform()
{
    limpiar();
    mostrarform(false);
}

function limpiar()
{
    document.getElementById("formregistros").reset();
}

function listar(estado)
{
    tabla=$('#tbllistado').dataTable(
    {
            "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [],
               language: {
      search: 'Buscar ',
      paginate: {
        first: 'Primero',
        previous: 'Anterior',
        next: 'Siguiente',
        last: 'Último'
      }},
        "ajax":
                            {
                                    url: '../Control/InventarioSoftwareControl.php?op=listar&estado='+estado,
                                    type : "get",
                                    dataType : "json",
                                    error: function(e){
                                            console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,
        "order": [[ 0, "asc" ]]
    }).DataTable();
}

function guardar(e)
{
    e.preventDefault();
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formregistros")[0]);
    $.ajax({
        url: "../Control/InventarioSoftwareControl.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            alert(datos);
            mostrarform(false);
            limpiar();
            tabla.ajax.reload();
        }
    });
    limpiar();
}

function mostrar(id)
{
    $.post("../Control/InventarioSoftwareControl.php?op=mostrar",{id : id}, function(data)
    {
    data = JSON.parse(data);
    mostrarform(true);
    $("#id").val(data.ID_INVENTARIO_SOFTWARE);
    $("#nombreapp").val(data.NOMBREAPP);
    $("#alcance").val(data.ALCANCE);
    $("#fecha").val(data.FECHA_ULTIMA_VERSION);
    $("#area").val(data.AREA_RESPONSABLE);
    $("#lider").val(data.LIDER_FUNCIONAL);
    $("#tipo").val(data.TIPO_DESARROLLO);
    $("#link").val(data.LINK_CARPETA);
    });
}

function anular(id){
     bootbox.confirm({
            message: 'Desea anular este registro?',
            buttons: {
                confirm: { label: 'SI' },
                cancel: { label: 'NO' }
            },
            callback: function (result) {
                if (result) {
                  $.post("../Control/InventarioSoftwareControl.php?op=anular",{id : id}, function(data)
            {
      bootbox.alert({
                        title: 'Desactivado!',
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

function activar(id){
     bootbox.confirm({
            message: "Desea activar este registro?",
            buttons: {
                confirm: { label: 'SI' },
                cancel: { label: 'NO' }
            },
            callback: function (result) {
                if (result) {
                  $.post("../Control/InventarioSoftwareControl.php?op=activar",{id : id}, function(data)
            {
      bootbox.alert({
                        title: 'Activado!',
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

init();