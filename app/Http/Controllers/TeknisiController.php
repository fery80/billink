<?php

namespace App\Http\Controllers;

class TeknisiController extends Controller
{
    public function index()
    {
        return view('teknisi.dashboard');
    }
}
