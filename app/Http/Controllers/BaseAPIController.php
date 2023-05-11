<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BaseAPIController extends BaseResponseController
{

    public function index(){
        $search_data = DB::connection('sqlsrv')->select("select name, database_name,public_id from tenants order by id asc");

        if(empty($search_data)){
            return $this->sendResponse($search_data, null);
        }
        return $this->sendResponse($search_data, "Data Ditemukan!");
    }

    public function parseKredit($kol){

        if($kol == 1){
            $kolektibilitas = "Lancar";
        }else if($kol == 2){
            $kolektibilitas = "Kurang Lancar";
        }else if($kol == 3){
            $kolektibilitas = "Diragukan";
        }else if($kol == 4){
            $kolektibilitas = "Macet";
        }else{
            $kolektibilitas = "-";
        }
        return $kolektibilitas;
    }

    public function parseSimpanan($tab){

        if($tab == 00){
            $produktab = "00";
        }else if($tab == 01){
            $produktab = "DEBITUR";
        }else if($tab == 02){
            $produktab = "UMUM";
        }else if($tab == 03){
            $produktab = "TERKAIT";
        }else if($tab == 04){
            $produktab = "KARYAWAN";
        }else if($tab == 06){
            $produktab = "06";
        }else if($tab == 07){
            $produktab = "07";
        }else{
            $produktab = "-";
        }
        return $produktab;
    }

    public function parseDeposito($dep){

        if($dep == 01){
            $produkdep = "1 BULAN";
        }else if($dep == 03){
            $produkdep = "3 BULAN";
        }else if($dep == 06){
            $produkdep = "6 BULAN";
        }else if($dep == 12){
            $produkdep = "12 BULAN";
        }else{
            $produkdep = "-";
        }
        return $produkdep;
    }

    public function getKSP(Request $request){
        $public_id = $request->input('public_id');
        if($public_id == "all"){ //apakah inputan dari dropdown yaitu gabungan
            $search_data = DB::connection('sqlsrv')->select("select * from tenants");
            $total_aset = 0;
            $total_laba = 0;
            $total_pendapatan = 0;
            $total_biaya = 0;

            foreach($search_data as $search){
                $explode = explode(';',$search->database_dsn);
                $connection = $search->database_name;
                $driver = 'sqlsrv';
                $host = str_replace('server=','',$explode[0]);
                $port = '1433';
                $database = $search->database_name;
                $username = str_replace('uid=','',$explode[3]);
                $password = str_replace('pwd=','',$explode[4]);

                Config::set(["database.connections.$connection" => [
                    'driver' => $driver,
                    'host' => $host,
                    'port' => $port,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset' => 'utf8',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'options' => extension_loaded('sqlsrv') ? array_filter([
                        "Database" => $database,
                        "UID" => $username,
                        "PWD" => $password,
                        "CharacterSet" => "UTF-8",
                    ]) : null,
                ]]);
                $query_ksp = DB::connection('mysql')->select("select * from queries");

                $aset = DB::connection($connection)->selectOne($query_ksp[4]->query);
                $laba = DB::connection($connection)->selectOne($query_ksp[7]->query);
                $pendapatan = DB::connection($connection)->selectOne($query_ksp[5]->query);
                $biaya = DB::connection($connection)->selectOne($query_ksp[6]->query);

                $total_aset += $aset->amount;
                $total_laba += $laba->amount;
                $total_pendapatan += $pendapatan->amount;
                $total_biaya += $biaya->amount;
            }
            $response_data['data'] = [
                $response_data['total_aset'] = intval($total_aset),
                $response_data['total_laba'] = intval($total_laba),
                $response_data['total_pendapatan'] = intval($total_pendapatan),
                $response_data['total_biaya'] = intval($total_biaya),
            ];
            $response_data['all'] = "all";
            return json_encode($response_data);
        }else{ //
            $search_data = DB::connection('sqlsrv')->selectOne("select * from tenants where public_id = $public_id");
            if(empty($search_data)){
            return $this->sendResponse($search_data,null);
            }else{
                $explode= explode(';',$search_data->database_dsn);
                $connection = $search_data->database_name;
                $driver = 'sqlsrv';
                $host = str_replace('server=','',$explode[0]);
                $port = '1433';
                $database = $search_data->database_name;
                $username = str_replace('uid=','',$explode[3]);
                $password = str_replace('pwd=','',$explode[4]);

                Config::set(["database.connections.$connection" => [
                    'driver' => $driver,
                    'host' => $host,
                    'port' => $port,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset' => 'utf8',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'options' => extension_loaded('sqlsrv') ? array_filter([
                        "Database" => $database,
                        "UID" => $username,
                        "PWD" => $password,
                        "CharacterSet" => "UTF-8",
                    ]) : null,
                ]]);
                $query_ksp = DB::connection('mysql')->select("select * from queries");
                $response_data['nama_ksp'] = $search_data->name;

                //Chart Pinjaman
                $sum_pinjaman = 0;
                $getDataPinjaman = DB::connection($connection)->select($query_ksp[0]->query);
                if(empty($getDataPinjaman)){
                    $response_data['total_pinjaman'] = 0;
                    $response_data['sum_total_pinjaman'] = $sum_pinjaman;
                }else{
                    foreach($getDataPinjaman as $tp){
                        $response_data['total_pinjaman'][] = [
                            'name' => $this->parseKredit($tp->kolektibilitas),
                            'noa' => $tp->noa,
                            'y' => floatval($tp->amount),
                        ];
                    }
                    foreach($response_data['total_pinjaman'] as $elemen){
                        $sum_pinjaman += $elemen['y'];
                    }
                    $response_data['sum_total_pinjaman'] = $sum_pinjaman;
                }

                // Chart Simpanan
                $sum_simpanan = 0;
                $getDataSimpanan = DB::connection($connection)->select($query_ksp[1]->query);
                if(empty($getDataSimpanan)){
                    $response_data['total_simpanan'] = 0;
                    $response_data['sum_total_simpanan'] = $sum_simpanan;
                }else{
                    foreach($getDataSimpanan as $ts){
                        $response_data['total_simpanan'][] = [
                            'name' => $this->parseSimpanan($ts->kodeproduktab),
                            'noa' => $ts->noa,
                            'y' => floatval($ts->amount),
                        ];
                    }
                    foreach($response_data['total_simpanan'] as $elemen){
                        $sum_simpanan += $elemen['y'];
                    }
                    $response_data['sum_total_simpanan'] = $sum_simpanan;
                }

                // Chart Simpanan Berjangka
                $sum_simpanan_berjangka = 0;
                $getDataDeposito = DB::connection($connection)->select($query_ksp[2]->query);
                if(empty($getDataDeposito)){
                    $response_data['total_simpanan_berjangka'] = 0;
                    $response_data['sum_total_simpanan_berjangka'] = $sum_simpanan_berjangka;
                }else{
                    foreach($getDataDeposito as $sb){
                        $response_data['total_simpanan_berjangka'][] = [
                            'name' => $this->parseDeposito($sb->kodeproduk),
                            'noa' => $sb->noa,
                            'y' => floatval($sb->amount),
                        ];
                    }
                    foreach($response_data['total_simpanan_berjangka'] as $elemen){
                        $sum_simpanan_berjangka += $elemen['y'];
                    }
                    $response_data['sum_total_simpanan_berjangka'] = $sum_simpanan_berjangka;
                }

                // Chart NPL
                $getDataNPL = DB::connection($connection)->selectOne($query_ksp[3]->query);
                $response_data['npl'] = [
                    'name' => 'NPL',
                    'percentage' => floatval($getDataNPL->pctg),
                    'y' => floatval($getDataNPL->amount),
                ];

                for($i = 4;$i < sizeof($query_ksp);$i++){
                    $response_data[$query_ksp[$i]->report_name] = DB::connection($connection)->selectOne($query_ksp[$i]->query);
                }
            }
            return $this->sendResponse($response_data,"Data Ditemukan!");
        }
    }
}
