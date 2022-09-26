<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Task;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function all(Request $request, $id){

        $deadline = strtotime($request->deadline);
        $done = $request->done;

        if ($deadline && $done) {
            $filter_result = Task::where('deadline', $deadline)
            ->where('done', $done)
            ->get();
        }elseif ($deadline) {
            $filter_result = Task::where("list_id", $id)
            ->where('deadline', $deadline)
            ->get();
        }elseif ($done) {
            $filter_result = Task::where("list_id", $id)
            ->where('done', $done)
            ->get();
        }else {
            $filter_result = Task::where("list_id", $id)->get();
        }

        return  $filter_result;
        
    }

    //Create Task
    public function store(Request $request)
    {
        $fields = $request->validate([
                'list_id' => 'required|exists:dailies,id',
                'title' => 'required',
                'description' => 'required',
                'deadline' => 'required|date_format:d-m-Y H:i|after:yesterday',
                'done' => 'required'
            ],
            [
                'list_id.exists' => 'Daily list with this id not exists'
            ]
            );

        $deadline = strtotime($fields['deadline']);
        $user_id = Auth::id();

        $user_daily = Daily::where('id', intval($fields['list_id']))->first();
        $user_id_daily = intval($user_daily["user_id"]);
        
       
        if ($user_id == $user_id_daily) {
            $task = Task::create([
                'list_id' => $fields['list_id'],
                'title' => $fields['title'],
                'description' => $fields['description'],
                'deadline' => $deadline,
                'done' => $fields['done']
            ]);

            $response = [
                'task' => $task
            ];
        }else {
            $response = [
                "message" => "You are not created ". $user_daily["title"]
            ];
        }
        
        return response($response);
    }
}
