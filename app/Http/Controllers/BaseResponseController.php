<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Http\Request;

class BaseResponseController extends Controller
{
    public function sendResponse($report_name){
        $data = Query::where('report_name','=',$report_name)->first();
        $response = [
            'success' => true,
            'data' => $data,
            'message' => 'Sukses'
        ];

        return json_encode($response);
    }
}
