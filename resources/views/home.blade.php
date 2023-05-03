@extends('layouts.layout')
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div id="judul" class="mt-3"></div>
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
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <figure class="highcharts-figure">
                <div id="chart1"></div>
            </figure>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <figure class="highcharts-figure">
                <div id="chart2"></div>
            </figure>
        </div>
    </div>
    <div id="hr"></div>
    <div class="row mb-3">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <figure class="highcharts-figure">
                <div id="chart3"></div>
            </figure>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <figure class="highcharts-figure">
                <div id="chart4"></div>
            </figure>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#select2').select2();

        $('#select2').on("change", function(){
            let public_id = $('#select2').val();

            $.ajax({
                url: "{{url('/api/getKSP')}}",
                data: {public_id: public_id, "_token": $('#csrf_token').val()},
                type: "POST",
            }).done(function(response){
                let data = JSON.parse(response);
                console.log(data.data.total_aset.amount)
                $('#total_aset').val(new Intl.NumberFormat('en-US').format(data.data.total_aset.amount))
                $('#total_pendapatan').val(new Intl.NumberFormat('en-US').format(data.data.total_pendapatan.amount))
                $('#total_biaya').val(new Intl.NumberFormat('en-US').format(data.data.total_biaya.amount))
                $('#laba_berjalan').val(new Intl.NumberFormat('en-US').format(data.data.laba_berjalan.amount))

                let judul = "";
                    judul += "<h2 class='text-center'>"+data.data.nama_ksp+"<h2>";
                $('#judul').html(judul);
                let hr = "";
                    hr += "<hr>";
                $('#hr').html(hr);

                Highcharts.chart('chart1', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: "Total Pinjaman",
                        align: 'center'
                    },
                    tooltip: {
                        pointFormat: 'NoA: <b>{point.noa}</b> <br>{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                        valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                            }
                        }
                    },
                    series: [{
                        name: 'Saldo',
                        colorByPoint: true,
                        data: data.data.total_pinjaman
                    }]
                });

                Highcharts.chart('chart2', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: "Total Simpanan",
                        align: 'center'
                    },
                    tooltip: {
                        pointFormat: 'NoA: <b>{point.noa}</b> <br>{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                        valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                            }
                        }
                    },
                    series: [{
                        name: 'Saldo',
                        colorByPoint: true,
                        data: data.data.total_simpanan
                    }]
                });

                Highcharts.chart('chart3', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: "Total Simpanan Berjangka",
                        align: 'center'
                    },
                    tooltip: {
                        pointFormat: 'NoA: <b>{point.noa}</b> <br>{series.name}: <b>{point.y}</b>'
                    },
                    accessibility: {
                        point: {
                        valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                            }
                        }
                    },
                    series: [{
                        name: 'Saldo',
                        colorByPoint: true,
                        data: data.data.total_simpanan_berjangka
                    }]
                });
            })
        })
    </script>
@endsection