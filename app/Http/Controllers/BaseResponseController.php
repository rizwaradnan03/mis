<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Http\Request;

class BaseResponseController extends Controller
{
    public function sendResponse($data,$message){
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        return json_encode($response);
    }
}
