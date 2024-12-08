<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
        
    public function login(Request $req) {
        return view("home");
    }

}
