@extends('backend.index')

@section('contenido')
    {{-- Desarrollo de la sección de seguimiento de mapas --}}
    <style>
        #map {
            width: 100%;
            height: 600px;
        }
        #map-buttons button {
            background-color: #007cbf;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: background 0.2s;
            cursor: pointer;
        }
        #map-buttons button:hover {
            background-color: #005f8a;
        }

        #map select {
            background: #fff;
            color: #333;
            border: 1px solid #007cbf;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 15px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            outline: none;
            cursor: pointer;
        }
        #map select:focus {
            border-color: #005f8a;
        }
    </style>
    <div id="map" style="position:relative;">
        <!-- Botones de interacción -->
        <div id="map-buttons" style="position:absolute;top:10px;right:10px;z-index:2;display:flex;flex-direction:column;gap:8px;">
            <button id="btn-clear" type="button" style="padding:6px 12px;">Limpiar puntos</button>
            <button id="btn-save" type="button" style="padding:6px 12px;">Guardar terreno</button>
            <button id="btn-close" type="button" style="padding:6px 12px;">Finalizar polígono</button>
        </div>
    </div>
    @if(!empty($poligonos) && count($poligonos) > 0)    
        @foreach($poligonos as $idx => $mapa)
            @php
                $geojson = is_string($mapa['geojson'] ?? null) ? json_decode($mapa['geojson'], true) : ($mapa['geojson'] ?? []);
                $coords = $geojson['geometry']['coordinates'][0] ?? [];
            @endphp
            <div class="card card-primary mt-3">
                <div class="card-header">
                    <h3 class="card-title">Puntos del terreno #{{ $idx + 1 }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Longitud</th>
                                    <th>Latitud</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coords as $i => $coord)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $coord[0] }}</td>
                                        <td>{{ $coord[1] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info mt-3">No hay polígonos guardados.</div>
    @endif

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.2/mapbox-gl.js"></script>
    <!-- Ejemplo: Agregar límites de países y departamentos -->
    <!-- Puedes descargar GeoJSON de países y departamentos desde https://geojson-maps.ash.ms/ y https://gadm.org/download_country_v3.html -->
    <script>
        // Usar la llave pública directamente
        mapboxgl.accessToken = 'pk.eyJ1IjoiaGFjaGU3MyIsImEiOiJjbWcwNG95cHcwOW82MnFvYmxyMnA3cTdjIn0.1sxZO8eFfD80sV5StfitXQ';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [-65.2619, -19.0475], // Coordenadas iniciales (ejemplo: La Paz, Bolivia)
            zoom: 12
        });

        // Mostrar polígonos guardados del usuario
        const poligonosGuardados = @json($poligonos ?? []);
        map.on('load', function () {
            // Países
            map.addSource('countries', {
                type: 'geojson',
                data: 'https://raw.githubusercontent.com/datasets/geo-countries/master/data/countries.geojson'
            });
            map.addLayer({
                id: 'country-borders',
                type: 'line',
                source: 'countries',
                paint: {
                    'line-color': '#ff0000',
                    'line-width': 1.5
                }
            });

            // Departamentos (ejemplo Bolivia, puedes cambiar la URL por tu propio archivo)
            map.addSource('departments', {
                type: 'geojson',
                data: 'https://raw.githubusercontent.com/codeforamerica/click_that_hood/master/public/data/bolivia-departments.geojson'
            });
            map.addLayer({
                id: 'department-borders',
                type: 'line',
                source: 'departments',
                paint: {
                    'line-color': '#0000ff',
                    'line-width': 1
                }
            });

            // Mostrar polígonos guardados
            poligonosGuardados.forEach(function(mapa, idx) {
                let geojson = mapa.geojson;
                if (typeof geojson === 'string') {
                    try { geojson = JSON.parse(geojson); } catch(e) { return; }
                }
                if (geojson && geojson.geometry && geojson.geometry.type === 'Polygon') {
                    map.addSource('user-polygon-' + idx, {
                        type: 'geojson',
                        data: geojson
                    });
                    map.addLayer({
                        id: 'user-polygon-fill-' + idx,
                        type: 'fill',
                        source: 'user-polygon-' + idx,
                        layout: {},
                        paint: {
                            'fill-color': '#00ff00',
                            'fill-opacity': 0.2
                        }
                    });
                    map.addLayer({
                        id: 'user-polygon-line-' + idx,
                        type: 'line',
                        source: 'user-polygon-' + idx,
                        layout: {},
                        paint: {
                            'line-color': '#00ff00',
                            'line-width': 2
                        }
                    });
                }
            });
        });

    // Variables para almacenar los puntos y capas
    let points = [];
    let layers = {};
    let markers = [];
    let polygonClosed = false;

        // Selector de capas
        const capas = {
            'Satélite': 'mapbox://styles/mapbox/satellite-v9',
            'Calles': 'mapbox://styles/mapbox/streets-v11',
            'Exterior': 'mapbox://styles/mapbox/outdoors-v11'
        };

        // Crear selector de capas
        const layerControl = document.createElement('select');
        for (const nombre in capas) {
            const option = document.createElement('option');
            option.value = capas[nombre];
            option.text = nombre;
            layerControl.appendChild(option);
        }
        layerControl.style.position = 'absolute';
        layerControl.style.top = '10px';
        layerControl.style.left = '10px';
        layerControl.style.zIndex = 1;
        document.getElementById('map').appendChild(layerControl);

        layerControl.addEventListener('change', function() {
            map.setStyle(this.value);
        });

        // Función para agregar puntos al mapa
        map.on('click', (e) => {
            if (polygonClosed) return;
            const coordinates = e.lngLat;
            points.push(coordinates);

            const marker = new mapboxgl.Marker()
                .setLngLat(coordinates)
                .addTo(map);
            markers.push(marker);

            // Dibujar el polígono si hay más de un punto
            if (points.length > 1) {
                drawPolygon();
            }
        });

        // Función para dibujar el polígono
        function drawPolygon() {
            if (map.getSource('terrain-boundary')) {
                map.removeLayer('terrain-boundary-line');
                map.removeLayer('terrain-boundary-fill');
                map.removeSource('terrain-boundary');
            }
            if (points.length < 2) return;
            let polyCoords = points.map(p => [p.lng, p.lat]);
            if (polygonClosed && polyCoords.length > 2) {
                polyCoords.push([points[0].lng, points[0].lat]); // cerrar polígono
            }
            map.addSource('terrain-boundary', {
                'type': 'geojson',
                'data': {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Polygon',
                        'coordinates': [polyCoords]
                    }
                }
            });
            map.addLayer({
                'id': 'terrain-boundary-fill',
                'type': 'fill',
                'source': 'terrain-boundary',
                'layout': {},
                'paint': {
                    'fill-color': '#007cbf',
                    'fill-opacity': 0.3
                }
            });
            map.addLayer({
                'id': 'terrain-boundary-line',
                'type': 'line',
                'source': 'terrain-boundary',
                'layout': {},
                'paint': {
                    'line-color': '#007cbf',
                    'line-width': 2
                }
            });
        }

        // Botón: Limpiar puntos
        document.getElementById('btn-clear').onclick = function() {
            points = [];
            polygonClosed = false;
            markers.forEach(m => m.remove());
            markers = [];
            if (map.getSource('terrain-boundary')) {
                map.removeLayer('terrain-boundary-line');
                map.removeLayer('terrain-boundary-fill');
                map.removeSource('terrain-boundary');
            }
        };

        // Botón: Finalizar polígono
        document.getElementById('btn-close').onclick = function() {
            if (points.length > 2) {
                polygonClosed = true;
                drawPolygon();
            }
        };

        // Botón: Guardar terreno (envía a backend)
        document.getElementById('btn-save').onclick = function() {
            if (points.length > 2) {
                // Construir GeoJSON del polígono
                const geojson = {
                    type: 'Feature',
                    geometry: {
                        type: 'Polygon',
                        coordinates: [points.map(p => [p.lng, p.lat])]
                    }
                };
                // Enviar por AJAX al backend
                fetch('/mapas/guardar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ geojson: geojson })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Terreno guardado correctamente.');
                    } else {
                        alert('Error al guardar el terreno.');
                    }
                })
                .catch(() => alert('Error de conexión al guardar terreno.'));
            } else {
                alert('Agrega al menos 3 puntos para guardar el terreno.');
            }
        };
    </script>
@endsection 