<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BaseAPIController extends BaseResponseController
{
    public function getResponse(Request $request){
        $public_id = $request->header('public_id');
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
            $query = DB::connection('mysql')->select("select * from queries");
            $response_data['nama_ksp'] = $search_data->name;
            foreach($query as $q){
                $response_data[$q->report_name] = DB::connection($connection)->select($q->query);
            }
        }
        return json_encode($response_data);
    }
}
