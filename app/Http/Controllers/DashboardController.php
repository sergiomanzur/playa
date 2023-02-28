<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DashboardController extends Controller
{

    public function main(): View
    {

        $user = Auth::user();
        $data = ['hello' => 'world'];

        

        return view('dashboard', [
            'data' => [
                'hello' => 'world',
                'user' => $user
            ]
        ]);
    }
}
