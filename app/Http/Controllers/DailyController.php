<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{
    public function index()
    {
        return Daily::all();
    }

    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'title' => 'required',
                'description' => 'required'
            ]
            );

        $daily = Daily::create([
            'user_id' => Auth::id(),
            'title' => $fields['title'],
            'description' => $fields['description'],
            'date' => date('d-m-Y')
        ]);

        $response = [
            'daily' => $daily
        ];

        return response($response);
    }
}
