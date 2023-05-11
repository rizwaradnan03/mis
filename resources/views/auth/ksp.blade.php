@extends('layouts.app-admin')
@section('style')
    <style>
    #select2 + .select2-container .select2-selection__rendered {
        font-size: 20px;
    }

    #select2 + .select2-container .select2-results__option {
        font-size: 20px;
    }
    </style>
@endsection
@section('content')
    <div id="judul"></div>
    <select class="form-control" id="select2" style="width: 100%; font-size: 20px;">
        <option value="#" disabled selected>--Pilih KSP--</option>
        <option value="all">Gabungan</option>
        @foreach ($search_data as $s)
            <option value="{{$s->public_id}}">{{str_replace('KSP ','',$s->name)}}</option>
        @endforeach
    </select>
    <input type="hidden" id="csrf_token" name="csrf_token" value="{{csrf_token()}}">

    <div class="row mt-3 mb-5">
        <div class="col-12 mb-3">
            <div class="card pt-2">
                <label class="form-label text-center fw-bold h3">TOTAL ASET</label>
                <div class="card-body input-group">
                    <span class="input-group-text h3" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control text-center" id="total_aset" value="0" style="font-size: 20px;" disabled>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card pt-2">
                <label class="form-label text-center fw-bold h3">TOTAL PENDAPATAN</label>
                <div class="card-body input-group">
                    <span class="input-group-text h3" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control text-right" id="total_pendapatan" value="0" style="font-size: 20px;" disabled>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card pt-2">
                <label class="form-label text-center fw-bold h3">TOTAL BIAYA</label>
                <div class="card-body input-group">
                    <span class="input-group-text h3" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control text-right" id="total_biaya" value="0" style="font-size: 20px;" disabled>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card pt-2">
                <label class="form-label text-center fw-bold h3">LABA BERJALAN</label>
                <div class="card-body input-group">
                    <span class="input-group-text h3" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control text-right" id="laba_berjalan" value="0" style="font-size: 20px;" disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 pt-3">
            <figure class="highcharts-figure">
                <div id="chart1"></div>
            </figure>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 pt-3">
            <figure class="highcharts-figure">
                <div id="chart2"></div>
            </figure>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 pt-3">
            <figure class="highcharts-figure">
                <div id="chart3"></div>
            </figure>
        </div>
    </div>
@endsection
@section('script')
<script src="{{asset('assets/js/ksp.js')}}"></script>
@endsection
