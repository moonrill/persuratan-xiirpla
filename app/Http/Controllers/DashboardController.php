<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Log;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data = [
            'user' => User::query()->count(),
            'jenis_surat' => JenisSurat::query()->count(),
            'surat' => Surat::query()->count(),
            'log' => Log::query()->count()
        ];

        return view('dashboard.index', $data);
    }
}
