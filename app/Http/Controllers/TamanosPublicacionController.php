<?php

namespace App\Http\Controllers;

class TamanosPublicacionController extends Controller
{
    public function index()
    {
        return view('catalogos.tamanos-publicacion.index');
    }
}