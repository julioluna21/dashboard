var tabla,tabla2;
 var input = document.getElementById('archivoCsvGasolina');
  var label = document.getElementById('uploadCsvLabelGasolina');
  var texto = label ? label.querySelector('.upload-csv-text') : null;
  var nombreBox = document.getElementById('uploadCsvFilenameGasolina');
  var nombreTexto = document.getElementById('uploadCsvFilenameTextGasolina');
  var btnQuitar = document.getElementById('uploadCsvRemoveGasolina');

  function inicio() {
    listar();
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

  function abrirModalCargaGasolina() {
  $('#modalCargarGasolina').modal('show');
}

function guardarCargaGasolina() {
  const mes = $('#mesCargaGasolina').val(); // formato "YYYY-MM" que entrega <input type="month">

  if (!mes) {
    alert('Selecciona el mes a cargar.');
    return;
  }
  if (!input.files || !input.files[0]) {
    alert('Selecciona un archivo Excel.');
    return;
  }

  // El mes siempre se carga completo: desde = día 1, hasta = último día de ese mes.
  // new Date(año, mesNumero, 0) es el truco estándar de JS para "el día 0 del mes
  // siguiente", que cae exactamente en el último día del mes que queremos.
  const [anio, mesNumero] = mes.split('-').map(Number);
  const desde = mes + '-01';
  const ultimoDia = new Date(anio, mesNumero, 0).getDate();
  const hasta = mes + '-' + String(ultimoDia).padStart(2, '0');

  if (!confirm('¿Desea cargar este archivo? Se borrarán los registros existentes de ' + mes + ' y se cargarán los nuevos.')) {
    return;
  }

  $('#btnGuardarGasolina').prop('disabled', true).text('Cargando...');
  $('#cargandoGuardarGasolina').show();

  Rcorrerarchivo(function (filas) {
    // Igual que en peajes: se parte el arreglo ya procesado en lotes fijos
    // ANTES de mandarlo, para no superar el límite de tamaño de request del
    // servidor con archivos grandes, y para dar feedback de progreso real.
    const TAM_LOTE = 400;
    const lotes = [];
    for (let i = 0; i < filas.length; i += TAM_LOTE) {
      lotes.push(filas.slice(i, i + TAM_LOTE));
    }

    let totalInsertados = 0;
    let totalOmitidos = 0;

    function enviarLote(indice) {
      if (indice >= lotes.length) {
        alert('Proceso finalizado: ' + totalInsertados + ' registros insertados, ' + totalOmitidos + ' omitidos.');
        $('#modalCargarGasolina').modal('hide');
        tabla.ajax.reload();
        $('#btnGuardarGasolina').prop('disabled', false).text('Guardar');
        $('#cargandoGuardarGasolina').hide();
        $('#mesCargaGasolina').val('');
        limpiarArchivo();
        return;
      }

      $('#cargandoGuardarGasolina').html(
        '<i class="fa fa-spinner fa-spin"></i> Cargando lote ' + (indice + 1) + ' de ' + lotes.length + '...'
      );

      $.ajax({
        url: '../Control/GasolinaRawControl.php',
        method: 'POST',
        dataType: 'json',
        data: {
          op: 'cargarCSV',
          desde: desde,
          hasta: hasta,
          borrar: indice === 0 ? '1' : '0', // solo el primer lote [0] borra el rango existente, para evitar que el segundo lote [1] borre lo que cargo el lote anterior.
          registros: JSON.stringify(lotes[indice])
        }
      }).done(function (respuesta) {
        totalInsertados += Number(respuesta.insertados || 0);
        totalOmitidos += Number(respuesta.omitidos || 0);
        enviarLote(indice + 1);
      }).fail(function () {
        alert('Ocurrió un error al cargar el lote ' + (indice + 1) + ' de ' + lotes.length + '. Insertados hasta el momento: ' + totalInsertados + '.');
        $('#btnGuardarGasolina').prop('disabled', false).text('Guardar');
        $('#cargandoGuardarGasolina').hide();
      });
    }

    enviarLote(0);
  });
}

// Excel guarda una fecha como un número: cantidad de días desde el 30/12/1899
// (con la hora como fracción del día). 25569 es la cantidad de días entre esa
// fecha base de Excel y el 01/01/1970 (la base de Date de JS) -- restándolo
// convertimos "días desde Excel" a "días desde JS" en un solo paso.
function excelSerialADate(serial) {
  const diasDesdeEpocaJs = Math.floor(serial - 25569);
  const fechaBase = new Date(diasDesdeEpocaJs * 86400 * 1000);

  const fraccionDia = serial - Math.floor(serial);
  let segundosTotales = Math.round(86400 * fraccionDia);
  const horas = Math.floor(segundosTotales / 3600);
  segundosTotales -= horas * 3600;
  const minutos = Math.floor(segundosTotales / 60);
  const segundos = segundosTotales - minutos * 60;

  return new Date(Date.UTC(
    fechaBase.getUTCFullYear(), fechaBase.getUTCMonth(), fechaBase.getUTCDate(),
    horas, minutos, segundos
  ));
}

function formatearFechaDesdeDate(fecha) {
  const pad = function (n) { return String(n).padStart(2, '0'); };
  return fecha.getUTCFullYear() + '-' + pad(fecha.getUTCMonth() + 1) + '-' + pad(fecha.getUTCDate())
    + ' ' + pad(fecha.getUTCHours()) + ':' + pad(fecha.getUTCMinutes()) + ':' + pad(fecha.getUTCSeconds());
}

// La columna `fecha` en el Excel real de gasolina NO viene siempre igual: la mayoría
// de las filas la traen como número de serie de Excel (fecha real de la celda), pero
// algunas filas cargadas a mano la traen como texto "DD/MM/YYYY HH:MM:SS". Hay que
// soportar los dos casos -- confirmado revisando el archivo real, no es hipotético.
function normalizarFechaGasolina(valor) {
  if (valor === null || valor === undefined || valor === '') return '';

  if (typeof valor === 'number') {
    return formatearFechaDesdeDate(excelSerialADate(valor));
  }

  valor = String(valor).trim();
  const partes = valor.split(' ');
  const fechaParte = partes[0];
  const horaParte = partes[1] || '00:00:00';

  const dmy = fechaParte.split('/');
  if (dmy.length !== 3) return '';

  const dia = dmy[0].padStart(2, '0');
  const mes = dmy[1].padStart(2, '0');
  const anio = dmy[2];

  return anio + '-' + mes + '-' + dia + ' ' + horaParte;
}

function listar(estado)
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
                                    url: '../Control/GasolinaRawControl.php?op=listar',//pagina que realiza la operación
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

function Rcorrerarchivo(callback) {
  if (!input) return;
  var archivo = input.files && input.files[0];
  if (!archivo) {
    alert('Seleccioná un archivo CSV.');
    return;
  }
  var obj = [];

 
  Papa.parse(archivo, {
    header: false,
    skipEmptyLines: true,
    complete: async function (resultado) {
      const datos = resultado.data;
      const totalFilas = datos.length - 1;
 
      for (let i = 1; i < datos.length; i++) {
      const fila = datos[i];

      const cliente = String(fila[0] ?? '').trim();
      const proveedor = String(fila[1] ?? '').trim();
      const nro_identificacion = String(fila[2] ?? '').trim();
      const codigo_sap = String(fila[3] ?? '').trim();
      const no_venta = String(fila[4] ?? '').trim();
      const fechaOriginal = fila[5];
      const fecha = normalizarFechaGasolina(fechaOriginal);
      const estacion = String(fila[6] ?? '').trim();
      const regional = String(fila[7] ?? '').trim();
      const id_eds = String(fila[8] ?? '').trim();
      const placa = String(fila[9] ?? '').trim();
      const conductor = String(fila[10] ?? '').trim();
      const combustible = String(fila[11] ?? '').trim();
      const cantidad = String(fila[12] ?? '').trim();
      const precio = String(fila[13] ?? '').trim();
      const unidad_venta = String(fila[14] ?? '').trim();
      const total_venta = String(fila[15] ?? '').trim();
      const kilometraje = String(fila[16] ?? '').trim();
       

      
          obj.push({
        cliente: cliente,
        proveedor: proveedor,
        nro_identificacion: nro_identificacion,
        codigo_sap: codigo_sap,
        no_venta: no_venta,
        fecha: fecha,
        estacion: estacion,
        regional: regional,
        id_eds: id_eds,
        placa: placa,
        conductor: conductor,
        combustible: combustible,
        cantidad: cantidad,
        precio: precio,
        unidad_venta: unidad_venta,
        total_venta: total_venta,
        kilometraje: kilometraje
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
inicio();

