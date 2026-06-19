
$('#selectConcesion').select2({
    width: '100%' ,
    
});
$('#selectPeaje').select2({
    width: '100%' ,
    
});

// Crear icono personalizado para peajes
function crearIconoPeaje(color = '#871F1B') {
    return L.divIcon({
        html: `<div style="background-color: ${color}; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); cursor: pointer;">
            <i class="fa fa-product-hunt" style="color: white; font-size: 18px;"></i>
        </div>`,
        iconSize: [35, 35],
        className: 'custom-peaje-icon'
    });
}

// Crear icono personalizado para peaje seleccionado
function crearIconoPeajeSeleccionado() {
    return L.divIcon({
        html: `<div style="background-color: #FFD700; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border: 4px solid #871F1B; box-shadow: 0 2px 8px rgba(0,0,0,0.5); cursor: pointer; animation: pulse 1s infinite;">
            <i class="fa fa-product-hunt" style="color: #871F1B; font-size: 22px;"></i>
        </div>`,
        iconSize: [45, 45],
        className: 'custom-peaje-icon-selected'
    });
}

// Agregar animación de pulso
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);

// Convertir coordenadas DMS (Grados, Minutos, Segundos) a Decimal
function convertDMSToDecimal(dms) {
    const regex = /(\d+)°(\d+)'([\d.]+)"([NSEW])/;
    const match = dms.trim().match(regex);
    
    if (!match) {
        console.error("Formato de coordenada inválido:", dms);
        return null;
    }
    
    const degrees = parseFloat(match[1]);
    const minutes = parseFloat(match[2]);
    const seconds = parseFloat(match[3]);
    const direction = match[4];
    
    let decimal = degrees + (minutes / 60) + (seconds / 3600);
    
    if (direction === 'S' || direction === 'W') {
        decimal = -decimal;
    }
    
    return decimal;
}

// Convertir coordenadas DMS a lat/lon
function obtenerCoordenadasDecimal(dmsString) {
    const [latDMS, lonDMS] = dmsString.split(/\s+/);
    return {
        lat: convertDMSToDecimal(latDMS),
        lon: convertDMSToDecimal(lonDMS)
    };
}

// Obtener lista única de concesiones
function obtenerConcesiones() {
    const concesiones = new Set(datosPerajes.map(p => p.concesion));
    return Array.from(concesiones).sort();
}

// Filtrar peajes por concesión
function filtrarPorConcesion(concesion) {
    if (concesion === '') {
        return datosPerajes;
    }
    return datosPerajes.filter(p => p.concesion === concesion);
}

// Inicializar el mapa
function inicializarMapa() {
    // Crear el mapa centrado en Colombia
    window.mapa = L.map('mapContainer').setView([4.5709, -74.2973], 5);
    
    // Agregar capas base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(window.mapa);
    
    // Grupo de marcadores
    window.marcadores = {};
    window.grupoMarcadores = L.featureGroup();
    window.mapa.addLayer(window.grupoMarcadores);
    
    // Guardar datos globalmente
    window.datosPerajes = datosPerajes;
    window.peajesFiltrados = datosPerajes;
    
    // Crear select de concesiones
    const selectConcesion = document.getElementById('selectConcesion');
    if (selectConcesion) {
        selectConcesion.innerHTML = '<option value="">-- Ver todas las concesiones --</option>';
        
        obtenerConcesiones().forEach(concesion => {
            const option = document.createElement('option');
            option.value = concesion;
            option.textContent = concesion;
            selectConcesion.appendChild(option);
        });
        
        selectConcesion.onchange =function() {
            actualizarFiltros();
        };
    }
    
    // Crear select de peajes
    const selectPeaje = document.getElementById('selectPeaje');
    if (selectPeaje) {
        selectPeaje.onchange = function() {
            if (this.value == '') {
              actualizarFiltros();
            }else{
        const peaje = peajesFiltrados.find((p, idx) => idx === parseInt(this.value));
        if (peaje) {
            limpiarMarcadores();
            mostrarMarcadorPeaje(peaje, peajesFiltrados.indexOf(peaje));
            actualizarTabla([peaje]);
        }
            }
        };
    }
    
    // Mostrar todos los peajes por defecto
    mostrarTodosMarcadores(datosPerajes);
    actualizarTabla(datosPerajes);
}

// Actualizar filtros y mostrar en mapa
function actualizarFiltros() {
    const selectConcesion = document.getElementById('selectConcesion');
    const selectPeaje = document.getElementById('selectPeaje');
    
    const concesionSeleccionada = selectConcesion ? selectConcesion.value : '';
    const peajeSeleccionado = selectPeaje ? selectPeaje.value : '';
    
    // Filtrar por concesión
    let peajesFiltrados = filtrarPorConcesion(concesionSeleccionada);
    window.peajesFiltrados = peajesFiltrados;
    
    // Actualizar select de peajes
    actualizarSelectPeajes(peajesFiltrados);
    
    // Limpiar mapa
    limpiarMarcadores();
    
    // Mostrar peajes
    if (peajeSeleccionado !== '') {
        const peaje = peajesFiltrados.find((p, idx) => idx === parseInt(peajeSeleccionado));
        if (peaje) {
            mostrarMarcadorPeaje(peaje, peajesFiltrados.indexOf(peaje));
            actualizarTabla([peaje]);
        }
    } else {
        mostrarTodosMarcadores(peajesFiltrados);
        actualizarTabla(peajesFiltrados);
    }
}

// Actualizar opciones en select de peajes
function actualizarSelectPeajes(peajes) {
    const selectPeaje = document.getElementById('selectPeaje');
    if (!selectPeaje) return;
    
    selectPeaje.innerHTML = '<option value="">-- Ver todos los peajes --</option>';
    
    peajes.forEach((peaje, index) => {
        const option = document.createElement('option');
        option.value = index;
        option.textContent = `${peaje.nombre} (${peaje.via})`;
        selectPeaje.appendChild(option);
    });
    
}

// Mostrar todos los marcadores
function mostrarTodosMarcadores(peajes) {
    peajes.forEach((peaje, index) => {
        const coords = obtenerCoordenadasDecimal(peaje.coordenadas);
        if (coords.lat !== null && coords.lon !== null) {
            const marcador = L.marker([coords.lat, coords.lon], {
                title: peaje.nombre,
                icon: crearIconoPeaje('#871F1B')
            }).bindPopup(`
                <div style="font-weight: bold; margin-bottom: 5px; color: #871F1B;">${peaje.nombre}</div>
                <div><strong>Concesión:</strong> ${peaje.concesion}</div>
                <div><strong>Vía:</strong> ${peaje.via}</div>
                <div><strong>Coordenadas:</strong> ${peaje.coordenadas}</div>
            `).addTo(window.grupoMarcadores);
            
            window.marcadores[index] = marcador;
        }
    });
    
    // Ajustar vista para ver todos los marcadores
    if (window.grupoMarcadores.getLayers().length > 0) {
        window.mapa.fitBounds(window.grupoMarcadores.getBounds(), {padding: [50, 50]});
    }
}

// Mostrar un marcador específico
function mostrarMarcadorPeaje(peaje, index) {
    const coords = obtenerCoordenadasDecimal(peaje.coordenadas);
    if (coords.lat !== null && coords.lon !== null) {
        const marcador = L.marker([coords.lat, coords.lon], {
            title: peaje.nombre,
            icon: crearIconoPeajeSeleccionado()
        }).bindPopup(`
            <div style="font-weight: bold; margin-bottom: 5px; color: #871F1B;">${peaje.nombre}</div>
            <div><strong>Concesión:</strong> ${peaje.concesion}</div>
            <div><strong>Vía:</strong> ${peaje.via}</div>
            <div><strong>Coordenadas:</strong> ${peaje.coordenadas}</div>
        `).openPopup().addTo(window.grupoMarcadores);
        
        window.marcadores[index] = marcador;
        window.mapa.setView([coords.lat, coords.lon], 12);
    }
}

// Limpiar marcadores
function limpiarMarcadores() {
    Object.values(window.marcadores).forEach(marcador => {
        window.grupoMarcadores.removeLayer(marcador);
    });
    window.marcadores = {};
}

// Actualizar tabla con información
function actualizarTabla(peajes) {
    let html = '<div class="panel-body table-responsive" > <div id="invoice"><div class="invoice overflow-auto"></div><table border="0" cellspacing="0" cellpadding="0" id="tbllistado" style="width:100%;" >';
    html += '<thead ><tr>';
    html += '<th style="text-align: center !important; min-width: 200px;">CONCESIÓN</th>';
    html += '<th style="text-align: center !important;min-width: 200px;">NOMBRE PEAJE</th>';
    html += '<th style="text-align: center !important; min-width: 200px;">SERVICIO IP/REV</th>';
    html += '<th style="text-align: center !important;min-width: 200px;">TOLIS ACTIVO</th>';
    html += '<th style="text-align: center !important;min-width: 300px;">VÍA</th>';
    html += '<th style="text-align: center !important;min-width: 200px;">COORDENADAS</th>';
    html += '</tr></thead>';
    html += '<tbody>';
    
    peajes.forEach((peaje, index) => {
        html += `<tr>
                    <td>${peaje.concesion}</td>
                    <td><strong>${peaje.nombre}</strong></td>
                    <td>${peaje.servicioIP}</td>
                    <td>${peaje.tolisActivo}</td>
                    <td>${peaje.via}</td>
                    <td>${peaje.coordenadas}</td>
                </tr>`;
    });
    
    html += '</tbody></table></div></div>';
    if ($.fn.DataTable.isDataTable(".turnos-table")) {//destruir si existe
       $(".turnos-table").DataTable().destroy();
       }
    document.getElementById('tablaPerajes').innerHTML = html;
      $("#tbllistado").DataTable({
  pageLength: 10,
  lengthMenu: [5, 10, 20, 50, 100, 200],
  dom: 'Blfrtip', //  permite mostrar los botones
  buttons: [
    {
      extend: 'excelHtml5',
      text: '📊 Exportar a Excel',
      title: 'Liquidación Completa',
      exportOptions: {
        modifier: {
          page: 'all' // Exporta TODAS las filas, no solo las visibles
        }
      }
    }
  ],
  language: {
    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
  },
  order: [[0, "asc"]] // ordena por la primera columna
}); 
}

// Cargar cuando esté listo
document.addEventListener('DOMContentLoaded', function() {
    if (typeof L !== 'undefined') {
        inicializarMapa();
    } else {
        const checkLeaflet = setInterval(function() {
            if (typeof L !== 'undefined') {
                clearInterval(checkLeaflet);
                inicializarMapa();
            }
        }, 100);
    }
});
