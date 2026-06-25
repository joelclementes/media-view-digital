<?php

namespace App\Http\Controllers;

class ViolenciaTemaController extends Controller
{
    public function index()
    {
        return view('catalogos.violencia-temas.index');
    }
}