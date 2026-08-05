var tabla,tabla2;
 var input = document.getElementById('archivoCsvFilas');
  var label = document.getElementById('uploadCsvLabelFilas');
  var texto = label ? label.querySelector('.upload-csv-text') : null;
  var nombreBox = document.getElementById('uploadCsvFilenameFilas');
  var nombreTexto = document.getElementById('uploadCsvFilenameTextFilas');
  var btnQuitar = document.getElementById('uploadCsvRemoveFilas');

function inicio() {
    listar();
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

$.post("../Control/MovimiesntosContablesControlTem.php?tipo=ultimafecha", function (response) {
  response = JSON.parse(response);
  if (response) {$("#smallFecha").text('Última fecha de registro: ' + response.Fecha_docto);}
});

$('#mesesCerrados').hide(); 

if (!input || !label) return;

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

  //$('#fechaInicioFilas').val('');
  //$('#fechaFinFilas').val('').removeAttr('min').removeAttr('max');

  $('#modalGenerarFilas').modal('show');
}

/*$(document).on('change', '#fechaInicioFilas', function () {
  const inicio = $(this).val();
  const fin = $('#fechaFinFilas');

  if (!inicio) {
    fin.removeAttr('min').removeAttr('max');
    return;
  }

  const fechaMax = new Date(inicio + 'T00:00:00');
  fechaMax.setDate(fechaMax.getDate() + 30);

  fin.attr('min', inicio);
  fin.attr('max', fechaMax.toISOString().split('T')[0]);

  if (fin.val() && (fin.val() < fin.attr('min') || fin.val() > fin.attr('max'))) {
    fin.val('');
  }
});*/

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
  /*if (diffDias > 30) {
    alert('El rango entre fecha inicio y fecha fin no puede ser mayor a 15 días.');
    return;
  }*/

  if (!confirm('¿Desea registrar la información para estas fechas? Se borrarán los registros existentes de esa empresa en ese rango y se volverán a registrar.')) {
    return;
  }


  const btnGuardar = $('#btnGuardarGenerarFilas');
  const btnCancelar = $('#btnCancelarGenerarFilas');
   btnGuardar.prop('disabled', true).text('Cargando...');
  btnCancelar.prop('disabled', true);
  $('#cargandoGenerarFilas').show();
  Rcorrerarchivo(function(datosProcesados) {
   console.log(" Datos validados:", datosProcesados);


   $.ajax({
    url: '../Control/MovimiesntosContablesControlTem.php',
    method: 'POST',
    data: {
      tipo: 'registrarMovimientoEmpresa',
      idCia: idCia,
      desde: desde.replace(/-/g, '/'),
      hasta: hasta.replace(/-/g, '/'),
      registros: JSON.stringify(datosProcesados)
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
                                    url: '../Control/MovimiesntosContablesControlTem.php?tipo=listar',//pagina que realiza la operación
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






function mostrarArchivo(nombre) {
    nombreTexto.textContent = nombre;
    nombreBox.classList.add('show');
    texto.style.display = 'none';
  }

  function limpiarArchivo() {
    input.value = '';
    nombreBox.classList.remove('show');
    texto.style.display = '';
  }

  input.addEventListener('change', function () {
    var archivo = input.files && input.files[0];
    if (!archivo) { limpiarArchivo(); return; }
    if (!/\.csv$/i.test(archivo.name)) {
      alert('El archivo debe tener extensión .csv');
      limpiarArchivo();
      return;
    }
    mostrarArchivo(archivo.name);
  });

  btnQuitar.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    limpiarArchivo();
  });

  ['dragenter', 'dragover'].forEach(function (evt) {
    label.addEventListener(evt, function (e) {
      e.preventDefault();
      e.stopPropagation();
      label.classList.add('dragover');
    });
  });

  ['dragleave', 'drop'].forEach(function (evt) {
    label.addEventListener(evt, function (e) {
      e.preventDefault();
      e.stopPropagation();
      label.classList.remove('dragover');
    });
  });

  label.addEventListener('drop', function (e) {
    var archivos = e.dataTransfer && e.dataTransfer.files;
    if (archivos && archivos.length) {
      input.files = archivos;
      input.dispatchEvent(new Event('change'));
    }
  });


 function Rcorrerarchivo(callback) {
  if (!input) return;
  var archivo = input.files && input.files[0];
  if (!archivo) {
    alert('Seleccioná un archivo CSV.');
    return;
  }
  var obj = [];

  const fechaInicio = $('#fechaInicioFilas').val().replace(/-/g, '/');
  const fechaFin = $('#fechaFinFilas').val().replace(/-/g, '/');
  const empresaSeleccionada = $('#selEmpresaFilas').val();

  Papa.parse(archivo, {
    header: false,
    skipEmptyLines: true,
    complete: async function (resultado) {
      const datos = resultado.data;
      const totalFilas = datos.length - 1;

      for (let i = 1; i < datos.length; i++) {
        const fila = datos[i];
        const periodo = fila[0].toUpperCase().trim();
        const anio = periodo.substring(0, 4);
        const empresa = fila[1].toUpperCase().trim();
        const nombre_empresa = fila[2].toUpperCase().trim();
        const tipo_documento = fila[3].toUpperCase().trim();
        const docto = fila[5].toUpperCase().trim();
        const periodo_docto = fila[8].toUpperCase().trim();
        const fecha_actualizacion_docto = formatearFecha(fila[9].trim());
        const fecha_aprobacion_docto = formatearFecha(fila[10].trim());
        const fecha_anulacion_docto = formatearFecha(fila[11].trim());
        const usuario_creacion_docto = fila[12].toUpperCase().trim();
        const usuario_aprobacion_docto = fila[13].toUpperCase().trim();
        const usuario_anulacion_docto = fila[14].toUpperCase().trim();
        const notas_docto = fila[15].toUpperCase().trim();
        const Fecha_docto = formatearFecha(fila[16].trim());
        const cuenta = fila[17].toUpperCase().trim();
        const nombre_auxiliar = fila[18].toUpperCase().trim();
        const cuenta_n1 = fila[19].toUpperCase().trim();
        const nombre_cuenta_n1 = fila[20].toUpperCase().trim();
        const cuenta_n2 = fila[21].toUpperCase().trim();
        const nombre_cuenta_n2 = fila[22].toUpperCase().trim();
        const cuenta_n3 = fila[23].toUpperCase().trim();
        const nombre_cuenta_n3 = fila[24].toUpperCase().trim();
        const cuenta_n4 = fila[25].toUpperCase().trim();
        const nombre_cuenta_n4 = fila[26].toUpperCase().trim();
        const co_movto = fila[29].toUpperCase().trim();
        const co = fila[30].toUpperCase().trim();
        const regional_co_movto = fila[31].toUpperCase().trim();
        const regional = fila[32].toUpperCase().trim();
        const desc_regional = fila[33].toUpperCase().trim();
        const unidad_de_negocio = fila[34].toUpperCase().trim();
        const nombre_unidad_de_negocio = fila[35].toUpperCase().trim();
        const tercero = fila[36].toUpperCase().trim();
        const nombre_tercero = fila[37].toUpperCase().trim();
        const grupo_ccosto = fila[38].toUpperCase().trim();
        const centro_de_costo = fila[39].toUpperCase().trim();
        const nombre_centro_de_costo = fila[40].toUpperCase().trim();
        const debitos = fila[45].toUpperCase().trim();
        const creditos = fila[46].toUpperCase().trim();
        const Deb_Libro_2 = fila[48].toUpperCase().trim();
        const cred_Libro_2 = fila[49].toUpperCase().trim();
        const Movto_libro2 = fila[50].toUpperCase().trim();
        

        if(Fecha_docto < fechaInicio || Fecha_docto > fechaFin) {
          alert("Error: la fila " + (i+1) + " tiene una fecha fuera del rango seleccionado. Por favor, verifique las fechas."+Fecha_docto);
          return;
        }

        if(empresaSeleccionada!=empresa) {
          alert("Error: la fila " + (i+1) + " tiene una empresa diferente a la seleccionada. Por favor, verifique las empresas.");
          return;
        } 
          obj.push({
            anio : anio,
            periodo: periodo,
            empresa: empresa,
            nombre_empresa: nombre_empresa,
            tipo_documento: tipo_documento,
            docto: docto,
            periodo_docto: periodo_docto,
            fecha_actualizacion_docto: fecha_actualizacion_docto,
            fecha_aprobacion_docto: fecha_aprobacion_docto,
            fecha_anulacion_docto: fecha_anulacion_docto,
            usuario_creacion_docto: usuario_creacion_docto,
            usuario_aprobacion_docto: usuario_aprobacion_docto,
            usuario_anulacion_docto: usuario_anulacion_docto,
            notas_docto: notas_docto,
            Fecha_docto: Fecha_docto,
            cuenta: cuenta,
            nombre_auxiliar: nombre_auxiliar,
            cuenta_n1: cuenta_n1,
            nombre_cuenta_n1: nombre_cuenta_n1,
            cuenta_n2: cuenta_n2,
            nombre_cuenta_n2: nombre_cuenta_n2,
            cuenta_n3: cuenta_n3,
            nombre_cuenta_n3: nombre_cuenta_n3,
            cuenta_n4: cuenta_n4,
            nombre_cuenta_n4: nombre_cuenta_n4,
            co_movto: co_movto,
            co: co,
            regional_co_movto: regional_co_movto,
            regional: regional,
            desc_regional: desc_regional,
            unidad_de_negocio: unidad_de_negocio,
            nombre_unidad_de_negocio: nombre_unidad_de_negocio,
            tercero: tercero,
            nombre_tercero: nombre_tercero,
            grupo_ccosto: grupo_ccosto,
            centro_de_costo: centro_de_costo,
            nombre_centro_de_costo: nombre_centro_de_costo,
            debitos: debitos,
            creditos: creditos,
            Deb_Libro_2: Deb_Libro_2,
            cred_Libro_2: cred_Libro_2,
            Movto_libro2: Movto_libro2
          });
      }

      // Ejecutar callback con los datos procesados
      if (typeof callback === "function") {
        callback(obj);
      }
    },
    error: function (err) {
      console.error("Error al procesar CSV:", err);
    }
  });
}


function formatearFecha(valor) {
  if (!valor) return "";
  valor = valor.trim();

  let dia, mes, año;
  let partes;

  if (valor.includes("/")) {
    partes = valor.split("/");
  } else if (valor.includes("-")) {
    partes = valor.split("-");
  } else {
    return "";
  }

  if (partes.length !== 3) return "";

  if (partes[0].length === 4) {
    // YYYY/MM/DD o YYYY-MM-DD
    [año, mes, dia] = partes;
  } else {
    // DD/MM/YYYY o DD-MM-YYYY
    [dia, mes, año] = partes;
  }

  mes = mes.padStart(2, "0");
  dia = dia.padStart(2, "0");

  return `${año}/${mes}/${dia}`;
}



inicio();