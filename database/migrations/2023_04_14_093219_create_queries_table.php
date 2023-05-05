<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->string('report_name_display');
            $table->text('query');
            $table->enum('flag',['0','1']);
            $table->timestamps();
        });
        DB::table('queries')->insert([
            [
                "report_name" => "total_pinjaman",
                "report_name_display" => "Total Pinjaman",
                "query" => "select kolektibilitas, count(*) noa, sum(bakidebet) amount from crdmaster where stsrekcrd = 1 and stsbar = 1 and oto = 1 group by kolektibilitas order by kolektibilitas asc",
                "flag" => "0"
            ],
            [
                "report_name" => "total_simpanan",
                "report_name_display" => "Total Simpanan",
                "query" => "select kodeproduktab, count(*) noa, sum(saldoakhir+saldo_iptw) amount from tabmaster where stsrektab in (1,2) and stsbar = 1 and oto = 1 group by kodeproduktab order by kodeproduktab asc",
                "flag" => "0"
            ],
            [
                "report_name" => "total_simpanan_berjangka",
                "report_name_display" => "Total Simpanan Berjangka",
                "query" => "select kodeproduk, count(*) noa, sum(saldoakhir) amount from deposito where stsrekdep = 1 and stsbar = 1 and oto = 1 group by kodeproduk order by kodeproduk asc",
                "flag" => "0"
            ],
            [
                "report_name" => "npl",
                "report_name_display" => "NPL",
                "query" => "select sum(bakidebet) / (select sum(bakidebet) from crdmaster where stsrekcrd = 1 and stsbar = 1 and oto = 1) * 100 pctg, sum(bakidebet) amount from crdmaster where stsrekcrd = 1 and stsbar = 1 and oto = 1 and kolektibilitas in (2,3,4)",
                "flag" => "0"
            ],
            [
                "report_name" => "total_aset",
                "report_name_display" => "Total Aset",
                "query" => "select sum(saldoakhir) amount from glneraca where substring(noacc7, 1, 1) = '1' and noacc7 not in (select noacc from glmaster where flagacc = 'RAK')",
                "flag" => "0"
            ],
            [
                "report_name" => "total_pendapatan",
                "report_name_display" => "Total Pendapatan",
                "query" => "select sum(saldoakhir) amount from glneraca where substring(noacc7, 1, 1) = '4'",
                "flag" => "0"
            ],
            [
                "report_name" => "total_biaya",
                "report_name_display" => "Total Biaya",
                "query" => "select sum(saldoakhir) amount from glneraca where substring(noacc7, 1, 1) = '5'",
                "flag" => "0"
            ],
            [
                "report_name" => "laba_berjalan",
                "report_name_display" => "Laba Berjalan",
                "query" => "select(select sum(saldoakhir) from glneraca where substring(noacc7, 1, 1) = '4') - (select sum(saldoakhir) from glneraca where substring(noacc7, 1, 1) = '5') amount",
                "flag" => "0"
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queries');
    }
};
