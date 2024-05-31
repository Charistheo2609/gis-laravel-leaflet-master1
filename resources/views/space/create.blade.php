@extends('layouts.app')

@section('style-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
  integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
  crossorigin="" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        #map {
            height: 500px;
        }
    </style>
@endsection


{{-- Pada view create.blade space ini kita kan menginput beberapa data yaitu 
nama space (tempat), deskripsi, gambar jika di perlukan, dan titik koordinat lokasi
Untuk cdn yang kita muat disini hampir sama dengan form create pada file view create centrepoint

--}}

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card rounded">
                    <div class="card-header">Tambah Space</div>
                    <div class="card-body">
                        {{-- action form yang mengarah ke route space.store untuk proses penyimpanan data --}}
                        <form action="{{ route('space.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="">Nama space</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Foto space</label><br>
                                <img id="previewImage" class="mb-2" src="#" width="100%" alt="">
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                    id="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="">Deskripsi</label>
                                <textarea name="content" class="form-control @error('content')
                                    is-invalid
                                @enderror" id="" cols="30" rows="10" placeholder="Deskripsi"></textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Lokasi</label>
                                <input type="text" name="location"
                                    class="form-control @error('location') is-invalid @enderror" id="location">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="map"></div>
                            <div class="form-group mt-3">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
    integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// fungsi ini akan berjalan ketika akan menambahkan gambar dimana fungsi ini
    // akan membuat preview image sebelum kita simpan gambar tersebut.      
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Ketika tag input file denghan class image di klik akan menjalankan fungsi di atas
    $("#image").change(function() {
        readURL(this);
    });
</script>
<script>
    var osmAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
    var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

    var osm = L.tileLayer(osmUrl, {
        maxZoom: 19,
        attribution: osmAttr
    });

    var map = L.map('map', {
        center: [{{ $centrepoint->location }}],
        zoom: 5,
        layers: [osm]
    });

    var markersLayer = new L.LayerGroup();
    map.addLayer(markersLayer);

    var curLocation = [{{ $centrepoint->location }}];
    map.attributionControl.setPrefix(false);

    var marker = new L.marker(curLocation, {
        draggable: 'false',
    });
    map.addLayer(marker);

    L.control.layers({
        "OpenStreetMap": osm
    }).addTo(map);
</script>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        })
    }, 3000)
</script>
@endpush