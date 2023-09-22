<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Surat;
use Illuminate\View\View;

class SuratController extends Controller
{
    public function index(): View
    {
        $data = [
            'surat' => Surat::with('jenis', 'surat')->get(),
            'jenis_surat' => JenisSurat::all()
        ];

        return view('dashboard.surat.index', $data);
    }
}
