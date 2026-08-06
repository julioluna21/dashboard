var tabla,tabla2;
 var input = document.getElementById('archivoCsvFilas');
  var label = document.getElementById('uploadCsvLabelFilas');
  var texto = label ? label.querySelector('.upload-csv-text') : null;
  var nombreBox = document.getElementById('uploadCsvFilenameFilas');
  var nombreTexto = document.getElementById('uploadCsvFilenameTextFilas');
  var btnQuitar = document.getElementById('uploadCsvRemoveFilas');

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
  const desde = $('#fechaInicioGasolina').val();
  const hasta = $('#fechaFinGasolina').val();

  if (!desde || !hasta) {
    alert('Selecciona la fecha inicio y la fecha fin.');
    return;
  }
  if (!input.files || !input.files[0]) {
    alert('Selecciona un archivo CSV.');
    return;
  }
  if (!confirm('¿Desea cargar este archivo? Se borrarán los registros existentes en ese rango de fechas y se cargarán los nuevos.')) {
    return;
  }

  $('#btnGuardarGasolina').prop('disabled', true).text('Cargando...');
  $('#cargandoGuardarGasolina').show();

  procesarCsvGasolina(desde, hasta, function (filas) {
    $.ajax({
      url: '../Control/GasolinaRawControl.php',
      method: 'POST',
      data: {
        op: 'cargarCSV',
        desde: desde,
        hasta: hasta,
        registros: JSON.stringify(filas)
      }
    }).done(function (respuesta) {
      alert(respuesta.mensaje || 'Proceso finalizado.');
      $('#modalCargarGasolina').modal('hide');
      tabla.ajax.reload();
    }).fail(function () {
      alert('Ocurrió un error al cargar el archivo.');
    }).always(function () {
      $('#btnGuardarGasolina').prop('disabled', false).text('Guardar');
      $('#cargandoGuardarGasolina').hide();
    });
  });
}

function procesarCsvGasolina(desde, hasta, callback) {
  const filas = [];

  Papa.parse(input.files[0], {
    header: false,
    skipEmptyLines: true,
    complete: function (resultado) {
      const datos = resultado.data;

      for (let i = 1; i < datos.length; i++) {  // i=1: nos saltamos la fila de encabezados
        const fila = datos[i];

        const cliente = (fila[0] || '').trim();
        const proveedor = (fila[1] || '').trim();
        const nro_identificacion = (fila[2] || '').trim();
        const codigo_sap = (fila[3] || '').trim();
        const no_venta = (fila[4] || '').trim();
        const fecha = (fila[5] || '').trim();
        const estacion = (fila[6] || '').trim();
        const regional = (fila[7] || '').trim();
        const id_eds = (fila[8] || '').trim();
        const placa = (fila[9] || '').trim();
        const conductor = (fila[10] || '').trim();
        const combustible = (fila[11] || '').trim();
        const cantidad = (fila[12] || '').trim();
        const precio = (fila[13] || '').trim();
        const unidad_venta = (fila[14] || '').trim();
        const total_venta = (fila[15] || '').trim();
        const kilometraje = (fila[16] || '').trim();

        if (fecha < desde || fecha > hasta) {
          alert('Error: la fila ' + (i + 1) + ' tiene una fecha fuera del rango seleccionado (' + fecha + ').');
          return; // no llama al callback -> no se manda nada al servidor
        }

        filas.push({
          cliente: cliente,
          // ... el resto de campos con el mismo nombre que usa la columna en gasolina_raw
          kilometraje: kilometraje
        });
      }

      callback(filas);
    }
  });
}

function listar() {
  tabla = $('#tbllistado').dataTable({
    "aProcessing": true,
    "aServerSide": true,
    dom: 'Bfrtip',
    buttons: [],
    "ajax": {
      url: '../Control/GasolinaRawControl.php?op=listar',
      type: "get",
      dataType: "json",
      error: function (e) { console.log(e.responseText); }
    },
    "bDestroy": true,
    "iDisplayLength": 10,
    "order": [[5, "desc"]]
  }).DataTable();
}