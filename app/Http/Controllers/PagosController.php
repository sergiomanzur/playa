<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Pagos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PagosController extends Controller
{
    public function index()
    {
        // Check if the user is logged in and their email is allowed
        $allowedEmails = ['sergiom2010@gmail.com', 'jprubio@gmail.com'];
        if (!Auth::check() || !in_array(Auth::user()->email, $allowedEmails)) {
            abort(403, 'Unauthorized access');
        }

        // Load the users for the user select field
        $users = User::all();

        $lotes = Lote::all();

        return view('pagos.index', compact('users','lotes'));
    }

    public function insert(Request $request)
    {
        $allowedEmails = ['sergiom2010@gmail.com', 'jprubio@gmail.com'];
        $userEmail = auth()->user()->email;

        if (!in_array($userEmail, $allowedEmails)) {
            abort(403);
        }

        $validatedData = $request->validate([
            'user.*' => 'required|exists:users,id',
            'lote.*' => 'required|exists:lotes,id',
            'cantidad.*' => 'required|numeric|min:0',
        ]);

        $pagos = collect($validatedData['user'])->zip($validatedData['lote'], $validatedData['cantidad'])->map(function ($data) {
            return [
                'user_id' => $data[0],
                'lote_id' => $data[1],
                'cantidad' => $data[2],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        });

        try {
            Pagos::insert($pagos->toArray());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Pagos inserted successfully'], 200);
    }
}
