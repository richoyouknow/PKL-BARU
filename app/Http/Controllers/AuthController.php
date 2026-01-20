<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|max:50',
        ]);
        if(Auth::attempt($request->only('email', 'password'), $request->remember)){
            if(Auth::user()->role == 'anggota') return redirect('/anggota');
            return redirect('/admin');
        }
        return back()->with('failed', 'Email atau password salah');
    }
    
    function register(Request $request){
        
         $request->validate([
            'name' =>  'required|max:50',
            'email' => 'required|email|max:50',
            'password' => 'required|max:50',
            'confirm_password' => 'required|max:50|min:8|same:password',
        ]);
        $request['status'] = "active";
        $user = User::create($request->all());
        Auth::login($user);
        return redirect('/loginn');
    }
    
    
   public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('beranda'); 
}
  
}

