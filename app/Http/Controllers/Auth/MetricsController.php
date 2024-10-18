<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function index()
    {
        // Your logic to fetch and return metrics
        return view('metrics.metrics');
    }
}
