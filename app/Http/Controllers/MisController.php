<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MisController extends BaseResponseController
{
    public function index(){
        $search_data = DB::connection('sqlsrv')->select("select name, database_name,public_id from tenants order by id asc");
        $title = 'Homepage';
        if(empty($search_data)){
            return $this->sendResponse($search_data, null);
        }
        return view('home', compact('search_data','title'));
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
            $getDataPinjaman= DB::connection($connection)->select($query_ksp[0]->query);
            foreach($getDataPinjaman as $tp){
                $response_data['total_pinjaman'][] = [
                    'name' => "Kolektibilitas ".$tp->kolektibilitas,
                    'y' => floatval($tp->amount),
                ];
            }
            for($i = 4;$i < sizeof($query_ksp);$i++){
                $response_data[$query_ksp[$i]->report_name] = DB::connection($connection)->selectOne($query_ksp[$i]->query);
            }            
        }
        return $this->sendResponse($response_data,"Data Ditemukan!");
    }
}
