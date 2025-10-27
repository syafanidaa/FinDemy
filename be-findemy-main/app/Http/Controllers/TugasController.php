<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tugas::with('mata_kuliah')->where('user_id',  Auth::id());

        if ($request->has('hari') && !empty($request->hari)) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->orderBy('deadline', 'asc')->get();

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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
