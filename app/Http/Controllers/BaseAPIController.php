<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;

class BaseAPIController extends BaseResponseController
{
    public function getResponse(Request $request){
        $search_data = Query::where('report_name','=',$request->report_name)->first();

        if(empty($search_data)){
            return $this->sendResponse($search_data,null);
        }

        return $this->sendResponse($search_data,"Success!");
    }
}
