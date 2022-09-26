<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimeZone extends Controller
{
    public function change(Request $request)
    {
        $request->validate([
            'timezone' => 'required|in:Europe/Madrid,Europe/Belgrade,Asia/Bahrain,Africa/Tripoli,America/Jamaica',
        ]
        );
        $user_id = Auth::id();
        $user_timezone = User::find($user_id)->first();
        if ($user_timezone) {
            $user_timezone->timezone = $request->timezone;
            $user_timezone->save();
        }
        
        return $user_timezone;
        

    }
}
