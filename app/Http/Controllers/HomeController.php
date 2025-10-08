<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class HomeController extends Controller
{
    public function index()
    {
        // Busca todos os personagens salvos no banco
        $characters = Character::all();

        // Envia para a view
        return view('home', compact('characters'));
    }
}
