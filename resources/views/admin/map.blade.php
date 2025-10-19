<div class="card shadow mt-3">
    <div class="card-body position-relative">
        <h5 class="mb-3">🗺️ Peta Lokasi Pelanggan</h5>
        <div id="map" style="height: 600px; border-radius: 10px; overflow: hidden;"></div>

        {{-- 🔍 Pencarian Pelanggan --}}
        <div id="searchBox" class="position-absolute top-0 end-0 m-3 p-2 bg-white shadow rounded"
             style="z-index:1000; width:260px;">
            <input type="text" id="searchPelanggan" class="form-control" placeholder="Cari nama / kode pelanggan...">
        </div>
    </div>
</div>

{{-- Leaflet dan Plugin --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- Geocoder (Search lokasi umum) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .popup-card {
        text-align: center;
        width: 230px;
        font-family: 'Segoe UI', sans-serif;
    }
    .popup-card img {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        object-fit: cover;
        margin-bottom: 8px;
        box-shadow: 0 0 8px rgba(0,0,0,0.3);
    }
    .popup-card h6 { margin: 0; font-weight: 700; font-size: 15px; }
    .popup-card p { font-size: 13px; margin: 2px 0; }
    .popup-card small { display: block; font-size: 12px; color: #666; }
    .popup-card .badge { font-size: 11px; padding: 4px 8px; }
    .popup-card .status { margin-top: 5px; font-size: 12px; font-weight: 600; }

    /* 🔴 Animasi berkedip untuk marker merah */
    .pulse {
        width: 16px;
        height: 16px;
        background: red;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(255,0,0,0.4);
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255,0,0,0.4); }
        70% { box-shadow: 0 0 0 15px rgba(255,0,0,0); }
        100% { box-shadow: 0 0 0 0 rgba(255,0,0,0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // =============================
    // Inisialisasi Peta
    // =============================
    var map = L.map('map', { preferCanvas: true }).setView([-2.5489, 118.0149], 5);

    // =============================
    // Layer Dasar
    // =============================
    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    });

    var esriSatelite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '&copy; Esri, Maxar, Earthstar Geographics'
    });

    var googleHybrid = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0','mt1','mt2','mt3'],
        attribution: '&copy; Google Maps'
    });

    osmLayer.addTo(map);

    map.on('tileerror', function (e) {
        console.warn('Tile error:', e.tile.src);
        if (!map.hasLayer(googleHybrid)) {
            map.addLayer(googleHybrid);
        }
    });

    // =============================
    // Data Pelanggan dari Laravel
    // =============================
    var pelanggan = @json($pelanggan ?? []);
    var pelangganLayer = L.layerGroup().addTo(map);
    var markerList = [];

    // Fungsi warna marker
    function getMarkerColor(p) {
        if (!p.status_pembayaran) return 'black';
        switch (p.status_pembayaran.toLowerCase()) {
            case 'lunas': return 'green';
            case 'belumbayar': return 'red';
            case 'telat': return 'yellow';
            default:
                if (p.status_langganan === 'nonaktif') return 'purple';
                return 'gray';
        }
    }

    // Fungsi membuat marker berwarna
    function createColoredMarker(lat, lng, color, pulse = false) {
        if (pulse) {
            // Marker animasi untuk "belumbayar"
            return L.divIcon({
                className: 'pulse',
                iconSize: [16, 16],
            });
        }
        return L.circleMarker([lat, lng], {
            radius: 9,
            fillColor: color,
            color: "#333",
            weight: 1,
            opacity: 1,
            fillOpacity: 0.9
        });
    }

    // Tambah semua marker pelanggan
    pelanggan.forEach(function(p) {
        if (p.latitude && p.longitude) {
            let fotoUrl = p.foto
                ? "{{ asset('storage') }}/" + p.foto
                : "https://via.placeholder.com/120?text=No+Image";

            let color = getMarkerColor(p);
            let isPulse = (color === 'red'); // belumbayar → animasi

            let marker;
            if (isPulse) {
                marker = L.marker([p.latitude, p.longitude], {
                    icon: createColoredMarker(p.latitude, p.longitude, color, true)
                });
            } else {
                marker = L.circleMarker([p.latitude, p.longitude], {
                    radius: 9,
                    fillColor: color,
                    color: "#333",
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.9
                });
            }

            // Popup info pelanggan
            let popupContent = `
                <div class="popup-card">
                    <img src="${fotoUrl}" alt="Foto ${p.nama_pelanggan}">
                    <h6>${p.nama_pelanggan}</h6>
                    <small>Kode: ${p.kode_pelanggan}</small>
                    <p>${p.alamat}</p>
                    <span class="badge bg-primary">${p.paket ? p.paket.nama_paket : '-'}</span>
                    <p class="status text-${p.status_langganan ?? 'secondary'}">${(p.status_langganan ?? 'baru').toUpperCase()}</p>
                    <small>📞 ${p.nomer_hp ?? '-'}</small>
                    ${p.email ? `<small>✉️ ${p.email}</small>` : ''}
                    <hr style="margin:6px 0;">
                    <small>💰 ${p.status_pembayaran ? p.status_pembayaran.toUpperCase() : 'PELANGGAN BARU'}</small><br>
                    <small>🗓️ Tagihan: ${p.tanggal_tagihan ?? '-'}</small><br>
                    <small>⚡ Aktivasi: ${p.tanggal_aktivasi ?? '-'}</small>
                </div>
            `;

            marker.bindPopup(popupContent);
            marker.on('mouseover', () => marker.openPopup());
            marker.on('mouseout', () => marker.closePopup());

            pelangganLayer.addLayer(marker);
            markerList.push({ marker, data: p });
        }
    });

    // =============================
    // 🔍 Fitur Cari Pelanggan
    // =============================
    const inputCari = document.getElementById('searchPelanggan');
    inputCari.addEventListener('keyup', function(e) {
        const keyword = e.target.value.toLowerCase().trim();
        if (!keyword) return;

        const found = markerList.find(m =>
            (m.data.nama_pelanggan && m.data.nama_pelanggan.toLowerCase().includes(keyword)) ||
            (m.data.kode_pelanggan && m.data.kode_pelanggan.toLowerCase().includes(keyword))
        );

        if (found) {
            const { marker } = found;
            map.setView(marker.getLatLng(), 17);
            marker.openPopup();
        }
    });

    // =============================
    // Geocoder (Cari lokasi umum)
    // =============================
    L.Control.geocoder({
        defaultMarkGeocode: true,
        placeholder: "Cari lokasi...",
        errorMessage: "Lokasi tidak ditemukan"
    }).on('markgeocode', function(e) {
        var center = e.geocode.center;
        map.setView(center, 15);
        L.marker(center).addTo(map).bindPopup(e.geocode.name).openPopup();
    }).addTo(map);

    // =============================
    // 📍 Lokasi Saya
    // =============================
    var userMarker, accuracyCircle;
    function pusatkanLokasiSaya() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;

                if (userMarker) map.removeLayer(userMarker);
                if (accuracyCircle) map.removeLayer(accuracyCircle);

                userMarker = L.marker([lat, lng], {
                    icon: L.icon({
                        iconUrl: "https://cdn-icons-png.flaticon.com/512/64/64113.png",
                        iconSize: [35, 35],
                        iconAnchor: [17, 34]
                    })
                }).addTo(map).bindPopup("📍 Lokasi Anda").openPopup();

                accuracyCircle = L.circle([lat, lng], {
                    radius: pos.coords.accuracy,
                    color: "blue",
                    fillColor: "#2196f3",
                    fillOpacity: 0.15
                }).addTo(map);

                map.setView([lat, lng], 14);
            });
        } else {
            alert("Browser tidak mendukung geolocation.");
        }
    }

    // Tombol lokasi
    var LokasiControl = L.Control.extend({
        onAdd: function() {
            var btn = L.DomUtil.create('button', 'btn btn-light shadow-sm');
            btn.innerHTML = '<i class="bi bi-crosshair"></i>';
            btn.style.width = "40px";
            btn.style.height = "40px";
            btn.style.borderRadius = "50%";
            btn.style.border = "1px solid #ccc";
            btn.title = "Pusatkan Lokasi Saya";
            L.DomEvent.disableClickPropagation(btn);
            L.DomEvent.disableScrollPropagation(btn);
            btn.onclick = function(e) {
                e.stopPropagation();
                pusatkanLokasiSaya();
            };
            return btn;
        }
    });
    L.control.lokasi = function(opts) { return new LokasiControl(opts); }
    L.control.lokasi({ position: 'bottomright' }).addTo(map);

    // =============================
    // Klik map → isi koordinat form
    // =============================
    var markerBaru;
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(6);
        var lng = e.latlng.lng.toFixed(6);
        if (markerBaru) map.removeLayer(markerBaru);
        markerBaru = L.marker([lat, lng]).addTo(map)
            .bindPopup(`📍 Titik baru: ${lat}, ${lng}`).openPopup();
        if (document.getElementById('latitude')) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }
    });

    // =============================
    // Layer Control & Legend
    // =============================
    L.control.layers({
        "🗺️ Peta Standar (OSM)": osmLayer,
        "🌍 Satelit (Esri)": esriSatelite,
        "🛰️ Hybrid (Google)": googleHybrid
    }, { "👥 Pelanggan": pelangganLayer }).addTo(map);

    // Legend warna
    var legend = L.control({ position: "bottomleft" });
    legend.onAdd = function (map) {
        var div = L.DomUtil.create("div", "info legend bg-white p-2 rounded shadow");
        div.innerHTML = `
            <h6 class="mb-1">🧭 Keterangan Warna</h6>
            <div><span style="background:red; width:15px; height:15px; display:inline-block; border-radius:50%;"></span> Belum Bayar</div>
            <div><span style="background:green; width:15px; height:15px; display:inline-block; border-radius:50%;"></span> Lunas</div>
            <div><span style="background:yellow; width:15px; height:15px; display:inline-block; border-radius:50%; border:1px solid #ccc;"></span> Telat</div>
            <div><span style="background:purple; width:15px; height:15px; display:inline-block; border-radius:50%;"></span> Nonaktif</div>
            <div><span style="background:black; width:15px; height:15px; display:inline-block; border-radius:50%;"></span> Pelanggan Baru</div>
        `;
        return div;
    };
    legend.addTo(map);

    // Fokus awal ke lokasi user
    pusatkanLokasiSaya();
});
</script>
