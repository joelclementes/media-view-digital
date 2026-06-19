<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuariosPortales extends Controller
{
    public function index()
    {
        return view('asignacion-portales.index');
    }
}
