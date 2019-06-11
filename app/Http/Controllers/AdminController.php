<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    //     $this->authorize('isAdmin');
    // }

    public function home()
    {
        return view('admin.home');
    }

    public function login(){
        return view('admin.login');
    }

    public function postLogin(Request $request){
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            //echo "hello 2019";
            return redirect('/admin');
        }
        //return $request->password;
        return redirect()->back()->getTargetUrl();
    }

    public function register(){
        return view('admin.register');
    }

    public function postRegister(Request $request){
        $this->validation($request);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request['password']),
            'type' => 'admin'
        ]);
        return redirect('/admin');
    }

    public function validation($request){
        return $this->validate($request,[
            'name'=>'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|max:255',

        ]);
    }

    public function user(){
        $user=User::where('type','user')
            ->orderBy('id','desc')
            ->get(['name','created_at']);
        return $user;
    }
}
