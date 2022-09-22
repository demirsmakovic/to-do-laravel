<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{

    //Show All Lists
    public function index()
    {
        return Daily::all();
    }

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
    public function search(Request $request)
    {
        $title=$request->title;
        $date=$request->date;
        $filter_result = Daily::where('title', 'like', '%'.$title.'%')
        ->where('date', $date)
        ->paginate(10);

        return $filter_result;
        
    }
}
