@extends('layouts.layout')
@section('style')
    <style>
        .highcharts-figure,
.highcharts-data-table table {
  min-width: 320px;
  max-width: 800px;
  margin: 1em auto;
}

.highcharts-data-table table {
  font-family: Verdana, sans-serif;
  border-collapse: collapse;
  border: 1px solid #ebebeb;
  margin: 10px auto;
  text-align: center;
  width: 100%;
  max-width: 500px;
}

.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}

.highcharts-data-table th {
  font-weight: 600;
  padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
  padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}

.highcharts-data-table tr:hover {
  background: #f1f7ff;
}

input[type="number"] {
  min-width: 50px;
}
.card-body{
    padding: 10px
}
    </style>
@endsection
@section('content')
    <div id="judul" class="mt-3"></div>
    <select class="form-control" id="select2">
        <option value="#" disabled selected>--Pilih KSP--</option>
        @foreach ($search_data as $s)
            <option value="{{$s->public_id}}">{{$s->name}}</option>
        @endforeach
    </select>
    <input type="hidden" id="csrf_token" name="csrf_token" value="{{csrf_token()}}">

    <div class="row mt-3">
        <div class="col-12 mb-2">
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
    <div class="row">
        <div class="col-6">
            <figure class="highcharts-figure">
                <div id="chart1"></div>
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
                url: "{{url('/getKSP')}}",
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

                Highcharts.chart('chart1', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: "Total Pinjaman",
                    align: 'left'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}</b>'
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
                    name: 'Amount',
                    colorByPoint: true,
                    data: data.data.total_pinjaman
                }]
                });
            })
        })
    </script>
@endsection