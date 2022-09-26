<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class DailyController extends Controller
{

    //Create Daily List
    public function store(Request $request)
    {
        $fields = $request->validate([
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

    //Edit Daily List
    public function update(Request $request, $id)
    {
        $daily = Daily::find($id);
        $daily->update($request->all());
        return $daily;
    }

    //Delete Daily List
    public function destroy($id)
    {
        $delete = Daily::destroy($id);
        if ($delete) {
            return response([
                "message" => "Daily list is deleted sucessfully"
            ]);
        }
    }

    //Filter by date and title
    public function all(Request $request)
    {
        $user_id = Auth::id();
        $title = $request->title;
        $date = $request->date;
        if ($title && $date) {
            $filter_result = Daily::where('user_id', $user_id)
            ->where('title', 'like', '%'.$title.'%')
            ->where('date', $date)
            ->paginate(10);
        }elseif ($title) {
            $filter_result = Daily::where('user_id', $user_id)
            ->where('title', 'like', '%'.$title.'%')
            ->paginate(10);
        }elseif ($date) {
            $filter_result = Daily::where('user_id', $user_id)
            ->where('date', $date)
            ->paginate(10);
        }else {
            $filter_result = Daily::where('user_id', $user_id)
            ->paginate(10);
        }
       

        return $filter_result;
        
    }
}
