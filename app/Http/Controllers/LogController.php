<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index() {
        // Traemos los logs con su usuario y ordenados por los más recientes
        $logs = \App\Models\Log::with('user')->latest()->get();
        return view('dashboard.admin.admin_logs', compact('logs'));
    }
}
