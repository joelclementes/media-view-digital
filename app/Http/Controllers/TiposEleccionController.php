<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TiposEleccionController extends Controller
{
    public function index()
    {
        return view('catalogos.tipos-eleccion.index');
    }
}
