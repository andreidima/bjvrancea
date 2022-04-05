<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CarteScanata;

class StatisticaController extends Controller
{
    public function statistica()
    {
        $cartiScanate = carteScanata::all();

        return view('statistica.index', compact('cartiScanate'));
    }
}
