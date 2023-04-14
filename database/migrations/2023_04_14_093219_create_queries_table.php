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
            $table->text('query');
            $table->enum('flag',['0','1']);
            $table->timestamps();
        });
        DB::table('queries')->insert([
            [
                "report_name" => "total_pinjaman",
                "query" => "select count(*) noa, sum(bakidebet) amount from crdmaster where stsrekcrd = 1 and stsbar = 1 and oto = 1;",
                "flag" => "0"
            ],
            [
                "report_name" => "total_simpanan",
                "query" => "select count(*) noa, sum(saldoakhir+saldo_iptw) amount from tabmaster where stsrektab in (1,2) and stsbar = 1 and oto = 1;",
                "flag" => "0"
            ],
            [
                "report_name" => "total_simpanan_berjangka",
                "query" => "select count(*) noa, sum(saldoakhir) amount from deposito where stsrekdep = 1 and stsbar = 1 and oto = 1;",
                "flag" => "0"
            ],
            [
                "report_name" => "total_aset",
                "query" => "select sum(saldoakhir) from glneraca where substring(noacc7, 1, 1) = '1' and noacc7 not in (select noacc from glmaster where flagacc = 'RAK');",
                "flag" => "0"
            ],
            [
                "report_name" => "total_pendapatan",
                "query" => "select sum(saldoakhir) from glneraca where substring(noacc7, 1, 1) = '4';",
                "flag" => "0"
            ],
            [
                "report_name" => "total_biaya",
                "query" => "select sum(saldoakhir) from glneraca where substring(noacc7, 1, 1) = '5';",
                "flag" => "0"
            ],
            [
                "report_name" => "laba_berjalan",
                "query" => "select(select sum(saldoakhir) from glneraca where substring(noacc7, 1, 1) = '4') - (select sum(saldoakhir) from glneraca where substring(noacc7, 1, 1) = '5');",
                "flag" => "0"
            ],
            [
                "report_name" => "npl",
                "query" => "select sum(bakidebet) / (select sum(bakidebet) from crdmaster where stsrekcrd = 1 and stsbar = 1 and oto = 1) * 100 pctg, sum(bakidebet) amount from crdmaster where stsrekcrd = 1 and stsbar = 1 and oto = 1 and kolektibilitas in (2,3,4);",
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
