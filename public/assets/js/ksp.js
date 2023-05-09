 //Mencegah User Melihat Source Code
 $(document).keydown(function(event) {
    if (event.ctrlKey && event.key === "u") {
        event.preventDefault();
    }
});

$('#select2').select2();
AOS.init();

$('#select2').on("change", function(){
    let public_id = $('#select2').val();

    $.ajax({
        url: "/api/getKSP",
        data: {public_id: public_id, "_token": $('#csrf_token').val()},
        type: "POST",
    }).done(function(response){
        let data = JSON.parse(response);

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
                text: "Total Pinjaman <br><br>" + "Total : Rp" + new Intl.NumberFormat('en-US').format(data.data.sum_total_pinjaman) + "<br>" + "NPL : " + data.data.npl.percentage,
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
                text: "Total Simpanan <br><br>" + "Total : Rp" + new Intl.NumberFormat('en-US').format(data.data.sum_total_simpanan),
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
                text: "Total Simpanan Berjangka <br><br>" + "Total : Rp" + new Intl.NumberFormat('en-US').format(data.data.sum_total_simpanan_berjangka),
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
