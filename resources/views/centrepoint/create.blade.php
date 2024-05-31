@extends('layouts.app')

@section('style-css')
    {{-- load cdn leaflet css --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
        integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
        crossorigin="" />
    <style>
        #map {
            height: 500px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card rounded">
                    <div class="card-header">Setup Titik Koordinat Peta</div>
                    <div class="card-body">
                        <form action="{{ route('centre-point.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="">Lokasi</label>
                                <input type="text" name="location"
                                    class="form-control @error('location') is-invalid @enderror" id="location">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="map"></div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    {{-- load cdn js leaflet --}}
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
        integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
        crossorigin=""></script>
    <script>
        // Menambah attribut pada leaflet
        var osmAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

        var osm = L.tileLayer(osmUrl, {
            maxZoom: 19,
            attribution: osmAttr
        });

        var map = L.map('map', {
            center: [-0.027364880592078204, 109.33623781932587],
            zoom: 5,
            layers: [osm]
        });

        var markersLayer = new L.LayerGroup();
        map.addLayer(markersLayer);

        var curLocation = [-0.027364880592078204, 109.33623781932587];
        map.attributionControl.setPrefix(false);

        var marker = new L.marker(curLocation, {
            draggable: 'false',
        });
        map.addLayer(marker);

        L.control.layers({
            "OpenStreetMap": osm
        }).addTo(map);

            // ketika marker di geser kita akan mengambil nilai latitude dan longitude
            // kemudian memasukkan nilai tersebut ke dalam properti input text dengan name-nya location
            marker.on('dragend', function(event) {
                var location = marker.getLatLng();
                marker.setLatLng(location, {
                    draggable: 'true',
                }).bindPopup(location).update();

                $('#location').val(location.lat + "," + location.lng).keyup()
            });

            // untuk fungsi di bawah akan mengambil nilai latitude dan longitudenya
            // dengan cara klik lokasi pada map dan secara otomatis marker juga akan ikut bergeser dan nilai
            // latitude dan longitudenya akan muncul pada input text location
            var loc = document.querySelector("[name=location]");
            map.on("click", function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                if (!marker) {
                    marker = L.marker(e.latlng).addTo(map);
                } else {
                    marker.setLatLng(e.latlng);
                }
                loc.value = lat + "," + lng;
            });
        </script>
                <script>
                    window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function() {
                            $(this).remove();
                        })
                    }, 3000)
    </script>
@endpush