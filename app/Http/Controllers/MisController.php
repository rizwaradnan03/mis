<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MisController extends BaseResponseController
{
    public function ksp(){
        if(Auth::check()){
            $search_data = DB::connection('sqlsrv')->select("select name, database_name,public_id from tenants where grup = 'polin' order by sort_order asc");
            $title = 'KSP';
            if(empty($search_data)){
                return $this->sendResponse($search_data, null);
            }
            return view('auth.ksp', compact('search_data','title'));
        }else{
            return redirect('/');
        }
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
        }
        return $kolektibilitas;
    }

    public function parseSimpanan($tab){

        if($tab == 00){
            $produktab = "SIMP 00";
        }else if($tab == 01){
            $produktab = "SIMP.DEBITUR";
        }else if($tab == 02){
            $produktab = "SIMP.UMUM";
        }else if($tab == 03){
            $produktab = "SIMP.TERKAIT";
        }else if($tab == 04){
            $produktab = "SIMP.KARYAWAN";
        }else if($tab == 06){
            $produktab = "SIMP 06";
        }else if($tab == 07){
            $produktab = "SIMP 07";
        }
        return $produktab;
    }

    public function parseDeposito($dep){

        if($dep == 01){
            $produkdep = "SIMP. BERJANGKA 1 BULAN";
        }else if($dep == 03){
            $produkdep = "SIMP. BERJANGKA 3 BULAN";
        }else if($dep == 06){
            $produkdep = "SIMP. BERJANGKA 6 BULAN";
        }else if($dep == 12){
            $produkdep = "SIMP. BERJANGKA 12 BULAN";
        }
        return $produkdep;
    }

    public function getKSP(Request $request){
        $public_id = $request->input('public_id');
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
            $getDataPinjaman = DB::connection($connection)->select($query_ksp[0]->query);
            foreach($getDataPinjaman as $tp){
                $response_data['total_pinjaman'][] = [
                    'name' => $this->parseKredit($tp->kolektibilitas),
                    'noa' => $tp->noa,
                    'y' => floatval($tp->amount),
                ];
            }

            // Chart Simpanan
            $getDataSimpanan = DB::connection($connection)->select($query_ksp[1]->query);
            foreach($getDataSimpanan as $ts){
                $response_data['total_simpanan'][] = [
                    'name' => $this->parseSimpanan($ts->kodeproduktab),
                    'noa' => $ts->noa,
                    'y' => floatval($ts->amount),
                ];
            }

            // Chart Simpanan Berjangka
            $getDataDeposito = DB::connection($connection)->select($query_ksp[2]->query);
            foreach($getDataDeposito as $sb){
                $response_data['total_simpanan_berjangka'][] = [
                    'name' => $this->parseDeposito($sb->kodeproduk),
                    'noa' => $sb->noa,
                    'y' => floatval($sb->amount),
                ];
            }

            // Chart NPL
            $getDataNPL = DB::connection($connection)->selectOne($query_ksp[3]->query);
            $response_data['npl'] = [
                'name' => 'NPL',
                'percentage' => intval($getDataNPL->pctg),
                'y' => floatval($getDataNPL->amount),
            ];

            for($i = 4;$i < sizeof($query_ksp);$i++){
                $response_data[$query_ksp[$i]->report_name] = DB::connection($connection)->selectOne($query_ksp[$i]->query);
            }
        }
        return $this->sendResponse($response_data,"Data Ditemukan!");
    }

}
