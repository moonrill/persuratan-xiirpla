<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratCreateRequest;
use App\Http\Requests\SuratUpdateRequest;
use App\Models\JenisSurat;
use App\Models\Surat;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SuratController extends Controller
{
    public function index(): View
    {
        $data = [
            'surat' => Surat::with('jenis', 'user')->orderByDesc('tanggal_surat')->get(),
            'jenis_surat' => JenisSurat::all()
        ];

        return view('dashboard.surat.index', $data);
    }

    public function store(SuratCreateRequest $request)
    {
        $data = $request->validated();

        if ($path = $request->file('file')) {
            $path = $path->storePublicly('', 'public');
            $data['file'] = $path;
        }

        $surat = Surat::query()->create($data);

        if (!$surat) {
            return response()->json([
                'message' => 'Failed create surat'
            ], 403);
        }

        return response()->json([
            'message' => 'Surat created'
        ], 201);
    }

    public function download(Request $request)
    {
        return Storage::download("public/$request->path");
    }

    public function update(SuratUpdateRequest $request)
    {
        $data = $request->validated();

        if (!$request->file('file')) {
            return 'surat tidak ada';
        }

        return 'surat ada';
    }

    public function delete(int $id)
    {
        $surat = Surat::query()->find($id);

        if (!$surat) {
            throw new HttpResponseException(response()->json([
                'message' => 'Not found'
            ])->setStatusCode(404));
        }

        // Deleting file
        Storage::delete("public/$surat->file");
        // Deleting surat
        $surat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus surat'
        ], 200);
    }
}
