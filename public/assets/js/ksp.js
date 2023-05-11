 //Mencegah User Melihat Source Code
 $(document).keydown(function(event) {
    if (event.ctrlKey && event.key === "u") {
        event.preventDefault();
    }
});

function terbilang(angka) {
    var huruf = ["", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS"];
    var hasil = "";

    if (angka < 12) {
        hasil = huruf[angka];
    } else if (angka < 20) {
        hasil = terbilang(angka - 10) + " BELAS";
    } else if (angka < 100) {
        hasil = terbilang(Math.floor(angka / 10)) + " PULUH " + terbilang(angka % 10);
    } else if (angka < 200) {
        hasil = "SERATUS " + terbilang(angka - 100);
    } else if (angka < 1000) {
        hasil = terbilang(Math.floor(angka / 100)) + " RATUS " + terbilang(angka % 100);
    } else if (angka < 2000) {
        hasil = "SERIBU " + terbilang(angka - 1000);
    } else if (angka < 1000000) {
        hasil = terbilang(Math.floor(angka / 1000)) + " RIBU " + terbilang(angka % 1000);
    } else if (angka < 1000000000) {
        hasil = terbilang(Math.floor(angka / 1000000)) + " JUTA " + terbilang(angka % 1000000);
    } else if (angka < 1000000000000) {
        hasil = terbilang(Math.floor(angka / 1000000000)) + " MILYAR " + terbilang(angka % 1000000000);
    } else if (angka < 1000000000000000) {
        hasil = terbilang(Math.floor(angka / 1000000000000)) + " TRILIUN " + terbilang(angka % 1000000000000);
    } else {
        hasil = "ANGKA TERLALU BESAR";
    }
    return hasil;
}

$('#select2').select2();
AOS.init();

$('#select2').on("change", function(){
    let public_id = $('#select2').val();
    $.blockUI({ message: '<h1>Data Sedang Diproses!</h1>' });
    $.ajax({
        url: "/api/getKSP",
        data: {public_id: public_id, "_token": $('#csrf_token').val()},
        type: "POST",
    }).done(function(response){
        let data = JSON.parse(response);

        if(data.all != null){
            $('#total_aset').val(new Intl.NumberFormat('en-US').format(data.total_aset))
            $('#total_pendapatan').val(new Intl.NumberFormat('en-US').format(data.total_pendapatan))
            $('#total_biaya').val(new Intl.NumberFormat('en-US').format(data.total_biaya))
            $('#laba_berjalan').val(new Intl.NumberFormat('en-US').format(data.total_laba))

            $('#aset_terbilang').html(terbilang(data.total_aset) + " RUPIAH")
            $('#pendapatan_terbilang').html(terbilang(data.total_pendapatan) + " RUPIAH")
            $('#biaya_terbilang').html(terbilang(data.total_biaya) + " RUPIAH")
            $('#laba_terbilang').html(terbilang(data.total_laba) + " RUPIAH")

            $('#chart1').css("visibility","hidden")
            $('#chart2').css("visibility","hidden")
            $('#chart3').css("visibility","hidden")
            $('#judul').css("visibility","hidden")

            $('#aset_terbilang').css("visibility","")
            $('#pendapatan_terbilang').css("visibility","")
            $('#biaya_terbilang').css("visibility","")
            $('#laba_terbilang').css("visibility","")
        }else{
            $('#total_aset').val(new Intl.NumberFormat('en-US').format(data.data.total_aset.amount))
            $('#total_pendapatan').val(new Intl.NumberFormat('en-US').format(data.data.total_pendapatan.amount))
            $('#total_biaya').val(new Intl.NumberFormat('en-US').format(data.data.total_biaya.amount))
            $('#laba_berjalan').val(new Intl.NumberFormat('en-US').format(data.data.laba_berjalan.amount))

            $('#chart1').css("visibility","")
            $('#chart2').css("visibility","")
            $('#chart3').css("visibility","")
            $('#judul').css("visibility","")

            $('#aset_terbilang').css("visibility","hidden")
            $('#pendapatan_terbilang').css("visibility","hidden")
            $('#biaya_terbilang').css("visibility","hidden")
            $('#laba_terbilang').css("visibility","hidden")

            let judul = "";
                judul += "<h1 class='text-center fw-bold'>"+data.data.nama_ksp+"<h1>";
            $('#judul').html(judul);

            Highcharts.chart('chart1', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: "Total Pinjaman <br><br>" + "Total Rekening: "+ +" <br><br> Tot.Saldo: Rp      " + new Intl.NumberFormat('en-US').format(data.data.sum_total_pinjaman) + "<br>" + "NPL : " + Number(data.data.npl.percentage.toFixed(2)) + "%",
                    align: 'center',
                    style: {
                        fontSize: '20px'
                    }
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
                            format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                            style: {
                                fontSize: '14px',
                            }
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
                    text: "Total Simpanan <br><br>" + "Total : Rp       " + new Intl.NumberFormat('en-US').format(data.data.sum_total_simpanan),
                    align: 'center',
                    style: {
                        fontSize: '20px'
                    }
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
                            format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                            style: {
                                fontSize: '10px',
                            }
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
                    text: "Total Simpanan Berjangka <br><br>" + "Total : Rp        " + new Intl.NumberFormat('en-US').format(data.data.sum_total_simpanan_berjangka),
                    align: 'center',
                    style: {
                        fontSize: '20px'
                    }
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
                            format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                            style: {
                                fontSize: '14px',
                            }
                        },
                    }
                },
                series: [{
                    name: 'Saldo',
                    colorByPoint: true,
                    data: data.data.total_simpanan_berjangka
                }]
            });
        }
    }).always(function() {
        $.unblockUI();
    });
})
