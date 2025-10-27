<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jadwal::with('mata_kuliah')->where('user_id',  Auth::id());

        if ($request->has('hari') && !empty($request->hari)) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->orderBy('jam_mulai', 'asc')->get();

        return response()->json([
            'message' => 'Jadwals retrieved successfully',
            'data' => $jadwals
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
        ]);

        $jadwal = Jadwal::create([
            'user_id' => $request->user()->id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen' => $request->dosen,
            'ruangan' => $request->ruangan,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan.',
            'data' => $jadwal
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen' => 'required|string|max:255',
            'ruangan' => 'required|string|max:255',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
        ]);

        $jadwal = Jadwal::where('user_id', $request->user()->id)->find($id);

        if (!$jadwal) {
            return response()->json([
                'message' => 'Jadwal tidak ditemukan atau tidak memiliki akses.'
            ], 404);
        }

        $jadwal->update([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen' => $request->dosen,
            'ruangan' => $request->ruangan,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil diperbarui.',
            'data' => $jadwal
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $jadwal = Jadwal::where('user_id', $request->user()->id)->find($id);

        if (!$jadwal) {
            return response()->json([
                'message' => 'Jadwal tidak ditemukan atau tidak memiliki akses.'
            ], 404);
        }

        $jadwal->delete();

        return response()->json([
            'message' => 'Jadwal berhasil dihapus.'
        ], 200);
    }
}
