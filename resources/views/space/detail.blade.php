@extends('layouts.app')

@section('style-css')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css"
        integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js"
        integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s=" crossorigin=""></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .leaflet-container {
            height: 400px;
            width: 600px;
            max-width: 100%;
            max-height: 100%;
        }

    </style>
@endsection

@section('content')
    <div class="container py-4 justify-content-center">
        <div class="row">
            <div class="col-md-6 col-xs-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <p>
                        <h4><strong>Nama Space :</strong></h4>
                        <h5>{{ $spaces->name }}</h5>
                        </p>

                        <p>
                        <h4><strong>Keterangan Space :</strong></h4>
                        <p>{{ $spaces->content }}</p>
                        </p>

                        <p>
                        <h4>
                            <strong>Foto</strong>
                        </h4>
                        <img class="img-fluid" width="200" src="{{ $spaces->getImage() }}"
                            alt="">
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('space.index') }}" class="btn btn-outline-primary">Kembali</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6">
                <div class="card">
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
    {{-- karena hanya akan menampilkan single data dari marker yang dipilih jadi kita tidak 
    melakukan looping untuk halaman detail ini --}}
@push('javascript')
    <script>
        var osmAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

        var osm = L.tileLayer(osmUrl, {
            maxZoom: 19,
            attribution: osmAttr
        });

        var map = L.map('map', {
            center: [{{ $spaces->location }}],
            zoom: 5,
            layers: [osm]
        });

        var markersLayer = new L.LayerGroup();
        map.addLayer(markersLayer);

        var curLocation = [{{ $spaces->location }}];
        map.attributionControl.setPrefix(false);

        var marker = new L.marker(curLocation, {
            draggable: 'false',
        });
        map.addLayer(marker);

        L.control.layers({
            "OpenStreetMap": osm
        }).addTo(map);
    </script>
@endpush