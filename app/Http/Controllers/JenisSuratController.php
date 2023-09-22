<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $data = [
            'jenis_surat' => JenisSurat::all()
        ];

        return view('dashboard.jenis-surat.index', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_surat' => ['required', 'max:40']
        ]);

        if ($data) {
            if ($request->input('id') !== null) {
                // TODO: Update Jenis Surat
                $jenis_surat = JenisSurat::query()->find($request->input('id'));
                $jenis_surat->fill($data);
                $jenis_surat->save();

                return response()->json([
                    'message' => 'Jenis surat berhasil diupdate!'
                ], 200);
            }

            $dataInsert = JenisSurat::create($data);
            if ($dataInsert) {
                return redirect()->to('/dashboard/surat/jenis')->with('success', 'Jenis surat berhasil ditambah');
            }
        }

        return redirect()->to('/dashboard/surat/jenis')->with('error', 'Gagal tambah data');
    }

    public function delete(int $id): JsonResponse
    {
        $jenis_surat = JenisSurat::query()->find($id)->delete();

        if ($jenis_surat):
            //Pesan Berhasil
            $pesan = [
                'success' => true,
                'pesan' => 'Data user berhasil dihapus'
            ];
        else:
            //Pesan Gagal
            $pesan = [
                'success' => false,
                'pesan' => 'Data gagal dihapus'
            ];
        endif;
        return response()->json($pesan);
    }
}
