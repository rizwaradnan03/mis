<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;

class BaseAPIController extends BaseResponseController
{
    public function getResponse($report_name){
        $search_data = Query::where('report_name','=',$report_name)->first();

        if(empty($search_data)){
            return $this->sendResponse($search_data,"Empty!");
        }

        return $this->sendResponse($search_data,"Success!");
    }
}
