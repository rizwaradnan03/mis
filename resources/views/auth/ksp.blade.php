@extends('layouts.app-admin')
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div id="judul"></div>
    <select class="form-control" id="select2" style="width: 100%;">
        <option value="#" disabled selected>--Pilih KSP--</option>
        @foreach ($search_data as $s)
            <option value="{{$s->public_id}}">{{$s->name}}</option>
        @endforeach
    </select>
    <input type="hidden" id="csrf_token" name="csrf_token" value="{{csrf_token()}}">

    <div class="row mt-3 mb-3">
        <div class="col-12 mb-3">
            <div class="card">
                <label class="form-label text-center fw-bold">Total Aset</label>
                <div class="card-body input-group">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control" id="total_aset" value="0" disabled>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card">
                <label class="form-label text-center fw-bold">Total Pendapatan</label>
                <div class="card-body input-group">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control" id="total_pendapatan" value="0" disabled>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card">
                <label class="form-label text-center fw-bold">Total Biaya</label>
                <div class="card-body input-group">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control" id="total_biaya" value="0" disabled>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
            <div class="card">
                <label class="form-label text-center fw-bold">Laba Berjalan</label>
                <div class="card-body input-group">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control" id="laba_berjalan" value="0" disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" data-aos="fade-right">
            <figure class="highcharts-figure">
                <div id="chart1"></div>
            </figure>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" data-aos="fade-left">
            <figure class="highcharts-figure">
                <div id="chart2"></div>
            </figure>
        </div>
    </div>
    <div id="hr"></div>
    <div class="row mb-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12" data-aos="fade-right">
            <figure class="highcharts-figure">
                <div id="chart3"></div>
            </figure>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 fade-left">
            <figure class="highcharts-figure">
                <div id="chart4"></div>
            </figure>
        </div>
    </div>
@endsection
@section('script')
<script src="{{asset('assets/js/home.js')}}"></script>
@endsection
