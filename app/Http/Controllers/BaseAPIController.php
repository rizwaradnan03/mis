<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseAPIController extends BaseResponseController
{
    public function getResponse(Request $request){
        $search_data = DB::connection('sqlsrv')->select("select * from tenants");

        return $this->sendResponse($search_data,"Success!");
    }
}
