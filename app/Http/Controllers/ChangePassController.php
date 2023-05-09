<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangePassController extends Controller
{
    public function changePass(){
        if(Auth::check()){
            $title = 'Ganti Password';
            return view('auth.change_password', compact('title'));
        }else{
            return redirect('/');
        }
    }

    public function getChangePass(Request $request){
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));

        $search_data = User::where('email','=',$email)->first();
        return json_encode($search_data);
    }

    public function postChangePass(Request $request){
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));

        $user = User::where('email','=',$email)->first();
        $user->email = $email;
        $user->password = $password;
        $user->save();

        Auth::logout();
        return redirect('/');
    }

}
