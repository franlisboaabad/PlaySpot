<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TutorialController extends Controller
{
    /**
     * Mostrar documentación y tutoriales del sistema
     */
    public function index()
    {
        return view('admin.tutoriales.index');
    }
}

