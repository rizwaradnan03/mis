<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Http\Request;

class BaseResponseController extends Controller
{
    public function sendResponse($data,$message){
        $success = true;
        if(empty($data)){
            $success = false;
            $data = null;
            $message = "Data Tidak Ditemukan!";
        }
        $response = [
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ];

        return json_encode($response);
    }
}
