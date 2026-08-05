var tabla,tabla2;

function inicio() {
    listar();
    listar2();
    $('#selEmpresaFilas').select2({
    width: '100%',
            placeholder: 'Seleccione Empresa...',
            allowClear: true,
            dropdownParent: $('#modalGenerarFilas')
}); 

$('#anio').select2({
    width: '100%',
            placeholder: 'Seleccione año...',
            allowClear: true,
            dropdownParent: $('#modalcierremes')
}); 

$('#mes').select2({
    width: '100%',
            placeholder: 'Seleccione mes...',
            allowClear: true,
            dropdownParent: $('#modalcierremes')
}); 

$.post("../Control/MovimiesntosContablesControl.php?tipo=ultimafecha", function (response) {
  $("#smallFecha").text('Última fecha de registro: ' + response.Fecha_docto);
});

$('#mesesCerrados').hide(); 

}

function tablas(op) {
if (op == 1) {
    $('#registros').show();
    $('#mesesCerrados').hide(); 
}else if (op == 2) {
    $('#registros').hide();
    $('#mesesCerrados').show(); 
}
}

// Debe coincidir con el arreglo $empresas del case 'generarFilasContables' en Ordenesapi.php
const EMPRESAS_CONTABLES = {
  1: 'REGENCY SERVICES DE COLOMBIA S.A.S',
  2: 'REGENCY HEALTH SERVICES S.A.S',
  3: 'PROTECCION DE INFRAESTRUCTURA COLOMBIA - PROTINCO LTDA',
  4: 'CONSORCIO RQS',
  5: 'REGENCY TECH S.A.S',
  6: 'TRANSPORTADORA DE VALORES ANDINA LTDA',
  7: 'CONSORCIO PEAJES CUNDINAMARCA 24',
  8: 'CONSORCIO PEAJES CUNDINAMARCA 24',
  9: 'CONSORCIO PEAJES 2526',
};

function abrirModalGenerarFilas() {
  const select = $('#selEmpresaFilas');
  if (select.children().length === 0) {
    Object.keys(EMPRESAS_CONTABLES).forEach(id => {
      select.append(`<option value="${id}">${id} - ${EMPRESAS_CONTABLES[id]}</option>`);
    });
  }

  $('#fechaInicioFilas').val('');
  $('#fechaFinFilas').val('').removeAttr('min').removeAttr('max');

  $('#modalGenerarFilas').modal('show');
}


function abrirModalCierreMes() {
  $('#modalcierremes').modal('show');
}
$(document).on('change', '#fechaInicioFilas', function () {
  const inicio = $(this).val();
  const fin = $('#fechaFinFilas');

  if (!inicio) {
    fin.removeAttr('min').removeAttr('max');
    return;
  }

  const fechaMax = new Date(inicio + 'T00:00:00');
  fechaMax.setDate(fechaMax.getDate() + 15);

  fin.attr('min', inicio);
  fin.attr('max', fechaMax.toISOString().split('T')[0]);

  if (fin.val() && (fin.val() < fin.attr('min') || fin.val() > fin.attr('max'))) {
    fin.val('');
  }
});

function guardarGenerarFilas() {
  const idCia = $('#selEmpresaFilas').val();
  const desde = $('#fechaInicioFilas').val();
  const hasta = $('#fechaFinFilas').val();

  if (!idCia || !desde || !hasta) {
    alert('Seleccioná la empresa y las dos fechas.');
    return;
  }

  const diffDias = Math.round((new Date(hasta + 'T00:00:00') - new Date(desde + 'T00:00:00')) / 86400000);

  if (diffDias < 0) {
    alert('La fecha fin no puede ser anterior a la fecha inicio.');
    return;
  }
  if (diffDias > 15) {
    alert('El rango entre fecha inicio y fecha fin no puede ser mayor a 15 días.');
    return;
  }

  if (!confirm('¿Desea actualizar la información para estas fechas? Se borrarán los registros existentes de esa empresa en ese rango y se volverán a registrar.')) {
    return;
  }

  const btnGuardar = $('#btnGuardarGenerarFilas');
  const btnCancelar = $('#btnCancelarGenerarFilas');

  btnGuardar.prop('disabled', true).text('Cargando...');
  btnCancelar.prop('disabled', true);
  $('#cargandoGenerarFilas').show();

  $.ajax({
    url: '../Control/MovimiesntosContablesControl.php',
    method: 'GET',
    data: {
      tipo: 'registrarMovimientoEmpresa',
      idCia: idCia,
      desde: desde.replace(/-/g, '/'),
      hasta: hasta.replace(/-/g, '/')
    }
  }).done(function (response) {
    alert(response.mensaje || 'Proceso finalizado.');
    $('#modalGenerarFilas').modal('hide');
    if (tabla && tabla.ajax) {
      tabla.ajax.reload();
    }
  }).fail(function (err) {
    console.error('Error al generar movimientos:', err);
    alert('Ocurrió un error al registrar la información.');
  }).always(function () {
    btnGuardar.prop('disabled', false).text('Guardar');
    btnCancelar.prop('disabled', false);
    $('#cargandoGenerarFilas').hide();
  });
}


function textoCorto(texto, max) {
  max = max || 60;
  if (!texto) return '';
  var str = String(texto).trim();
  if (str.length <= max) return str;
  var completo = str.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  return '<span style="cursor:pointer;" '
    + 'data-toggle="popover" data-trigger="click" data-placement="top" '
    + 'data-container="body" title="Texto completo" '
    + 'data-content="' + completo + '">'
    + str.substring(0, max) + '&hellip;</span>';
}



function listar()
{
    tabla=$('#tbllistado').dataTable(//Carga variable con datos datatable
    {
            "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla Bfrtip
        buttons: [
                        
                    ],
        "ajax"://metodo ajax
                            {
                                    url: '../Control/MovimiesntosContablesControl.php?tipo=listar',//pagina que realiza la operación
                                    type : "get",//tipo de envio de datos
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                    console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[ 14, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();

}

function listar2()
{
    tabla2=$('#tbllistadomeses').dataTable(//Carga variable con datos datatable
    {
            "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla Bfrtip
        buttons: [
                        
                    ],
        "ajax"://metodo ajax
                            {
                                    url: '../Control/MovimiesntosContablesControl.php?tipo=listarcierreperiodo',//pagina que realiza la operación
                                    type : "get",//tipo de envio de datos
                                    dataType : "json",//tipo de datos
                                    error: function(e){//si error muestra mensaje
                                    console.log(e.responseText);
                                    }
                            },
            "bDestroy": true,
            "iDisplayLength": 10,//Paginación
        "order": [[0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();

}


function guardarcierre() {
  const mes = $('#mes').val();
  const anio = $('#anio').val();

  if (!mes || !anio) {
    alert('Seleccioná el mes y el año.');
    return;
  }
  const btnGuardar = $('#btnGuardarmeses');
  const btnCancelar = $('#btnCancelarmeses');

  btnGuardar.prop('disabled', true).text('Cargando...');
  btnCancelar.prop('disabled', true);
  $.ajax({
    url: '../Control/MovimiesntosContablesControl.php',
    method: 'GET',
    data: {
      tipo: 'cerrarperiodo',
      mes: mes,
      anio: anio
    }
  }).done(function (response) {
    alert(response.mensaje || 'Proceso finalizado.');
    if (tabla2 && tabla2.ajax) {
      tabla2.ajax.reload();
    }
  }).fail(function (err) {
    alert('Ocurrió un error al registrar la información.');
  }).always(function () {
    btnGuardar.prop('disabled', false).text('Guardar');
    btnCancelar.prop('disabled', false);  
    $('#mes').val("");
    $('#anio').val("");
  });
}

inicio();