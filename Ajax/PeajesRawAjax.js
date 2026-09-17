var tabla, tabla2;
var input = document.getElementById('archivoCsvPeajes');
var label = document.getElementById('uploadCsvLabelPeajes');
var texto = label ? label.querySelector('.upload-csv-text') : null;
var nombreBox = document.getElementById('uploadCsvFilenamePeajes');
var nombreTexto = document.getElementById('uploadCsvFilenameTextPeajes');
var btnQuitar = document.getElementById('uploadCsvRemovePeajes');

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

function abrirModalCargaPeajes() {
    $('#modalCargarPeajes').modal('show');
}

function guardarCargaPeajes() {
  const mes = $('#mesCargaPeajes').val();

  if (!mes) {
    alert('Selecciona el mes a cargar.');
    return;
  }
  if (!input.files || !input.files[0]) {
    alert('Selecciona un archivo CSV.');
    return;
  }

  const [anio, mesNumero] = mes.split('-').map(Number);
  const desde = mes + '-01';
  const ultimoDia = new Date(anio, mesNumero, 0).getDate();
  const hasta = mes + '-' + String(ultimoDia).padStart(2, '0');

  if (!confirm('¿Desea cargar este archivo? Se borrarán los registros existentes de ' + mes + ' y se cargarán los nuevos.')) {
    return;
  }

  $('#btnGuardarPeajes').prop('disabled', true).text('Cargando...');
  $('#cargandoGuardarPeajes').show();

  Rcorrerarchivo(function (filas) {
    // Igual que en movimientos: se parte el arreglo ya procesado en lotes fijos
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
        $('#modalCargarPeajes').modal('hide');
        tabla.ajax.reload();
        $('#btnGuardarPeajes').prop('disabled', false).text('Guardar');
        $('#cargandoGuardarPeajes').hide();
        $('#mesCargaPeajes').val('');
        limpiarArchivo();
        return;
      }

      $('#cargandoGuardarPeajes').html(
        '<i class="fa fa-spinner fa-spin"></i> Cargando lote ' + (indice + 1) + ' de ' + lotes.length + '...'
      );

      $.ajax({
        url: '../Control/PeajesRawControl.php',
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
        $('#btnGuardarPeajes').prop('disabled', false).text('Guardar');
        $('#cargandoGuardarPeajes').hide();
      });
    }

    enviarLote(0);
  });
}

// El CSV de peajes trae la fecha como texto "DD/MM/AA HH:MM:SS" (año de 2 dígitos),
// NO como número serial de Excel como en gasolina. Asumimos siglo 20XX: "26" -> "2026".
// Si algún día llega un registro anterior al año 2000, esta regla se rompe -- pero
// para datos de peajes actuales es una suposición razonable.
function normalizarFechaPeajes(valor) {
    if (valor === null || valor === undefined || valor === '') return '';

    valor = String(valor).trim();
    const partes = valor.split(' ');
    const fechaParte = partes[0];
    const horaParte = partes[1] || '00:00:00';

    const dmy = fechaParte.split('/');
    if (dmy.length !== 3) return '';

    const dia = dmy[0].padStart(2, '0');
    const mes = dmy[1].padStart(2, '0');
    let anio = dmy[2];
    if (anio.length === 2) {
        anio = '20' + anio;
    }

    return anio + '-' + mes + '-' + dia + ' ' + horaParte;
}

// Los valores monetarios del CSV traen puntos como separador de miles
// ("1.071.508", "-16.100"). Si se insertan tal cual en una columna DECIMAL,
// MySQL interpreta el primer punto como separador decimal y trunca el valor
// (1.071.508 -> 1.07). Hay que quitar los puntos de miles antes de enviarlo.
// El signo negativo se conserva (los valores de reversa/ajuste vienen así).
function limpiarValorMonetario(valor) {
    if (valor === null || valor === undefined || valor === '') return '';
    valor = String(valor).trim();
    return valor.replace(/\./g, '');
}

function listar(estado) {
    tabla = $('#tbllistado').dataTable({
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
            }
        },
        "ajax": {
            url: '../Control/PeajesRawControl.php?op=listar',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    }).DataTable();
}

async function detectarEncoding(archivo) {
  const buffer = await archivo.slice(0, 4096).arrayBuffer();
  try {
    new TextDecoder('utf-8', { fatal: true }).decode(buffer);
    return 'UTF-8';
  } catch (e) {
    return 'ISO-8859-1';
  }
}

async function Rcorrerarchivo(callback) {
  if (!input) return;
  var archivo = input.files && input.files[0];
  if (!archivo) {
    alert('Seleccioná un archivo CSV.');
    return;
  }

  const encoding = await detectarEncoding(archivo);
  console.log('Encoding detectado:', encoding);
  var obj = [];

  Papa.parse(archivo, {
    header: false,
    skipEmptyLines: true,
    encoding: encoding,
    complete: async function (resultado) {
      const datos = resultado.data;

      const indiceEncabezado = datos.findIndex(fila =>
        fila.some(celda => String(celda ?? '').trim() === 'FECHA RECEPCIÓN')
      );

      if (indiceEncabezado === -1) {
        alert('No se encontró el encabezado esperado ("FECHA RECEPCIÓN") en el archivo. Verifica el formato del CSV.');
        return;
      }

      for (let i = indiceEncabezado + 1; i < datos.length; i++) {
        const fila = datos[i];

        const fecha_recepcion = normalizarFechaPeajes(fila[0]);
        const fecha_emision = normalizarFechaPeajes(fila[1]);
        const tipo_transaccion = String(fila[2] ?? '').trim();
        const codigo_transaccion = String(fila[3] ?? '').trim();
        const placa = String(fila[4] ?? '').trim();
        const categoria = String(fila[5] ?? '').trim();
        const peaje = String(fila[6] ?? '').trim();
        const carril = String(fila[7] ?? '').trim();
        const sentido = String(fila[8] ?? '').trim();
        const valor_inicial = limpiarValorMonetario(fila[9]);
        const valor_cobrado = limpiarValorMonetario(fila[10]);
        const valor_final = limpiarValorMonetario(fila[11]);
        const receptor_facturacion = String(fila[12] ?? '').trim();
        const cufe_dian = String(fila[13] ?? '').trim();

        if (!placa && !codigo_transaccion) continue;

        obj.push({
          fecha_recepcion, fecha_emision, tipo_transaccion, codigo_transaccion,
          placa, categoria, peaje, carril, sentido,
          valor_inicial, valor_cobrado, valor_final,
          receptor_facturacion, cufe_dian
        });
      }

      if (typeof callback === "function") {
        callback(obj);
      }
    },
    error: function (err) {
      console.error("Error al procesar CSV:", err);
    }
  });}
inicio();