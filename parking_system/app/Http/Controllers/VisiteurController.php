<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;


class VisiteurController extends Controller
{

    public function index(Request $request)
    {
        return view('visiteur.dashboard');
    }
}
