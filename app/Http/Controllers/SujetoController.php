<?php

namespace App\Http\Controllers;

// use App\Models\Sujeto;
use Illuminate\Http\Request;

class SujetoController extends Controller
{
    public function index()
    {
        return view('catalogos.sujetos.index');
    }
}
