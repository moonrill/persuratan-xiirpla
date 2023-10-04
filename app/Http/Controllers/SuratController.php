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
        $surat = Surat::query()->find($request->id);

        if ($path = $request->file('file')) {
            // Delete old file
            if ($surat->file) {
                Storage::delete("public/$surat->file");
            }

            // Store new file
            $path = $path->storePublicly('', 'public');
            $data['file'] = $path;
        }

        $surat->fill($data)->save();

        return [
            'message' => 'Berhasil update surat!'
        ];
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

    public function deleteFile(int $id)
    {
        $surat = Surat::query()->find($id);

        // Deleting file
        Storage::delete("public/$surat->file");

        // Set null on path surat in database
        $surat->fill(['file' => null])->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus file!'
        ], 200);
    }
}
