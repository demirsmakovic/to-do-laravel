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
    //Show All Tasks
    public function all(Request $request, $id){

        $deadline = strtotime($request->deadline);
        $done = $request->done;
        $user_id = Auth::id();
        $currentuser = User::find($user_id);
        $usertimezone = $currentuser->timezone;
       
        $daily_id = Daily::find($id);

        $list_id = Daily::where("id", $id)->first();
        

        if ($daily_id && $user_id == $list_id->user_id) {
            if ($deadline && $done) {
                $filter_result = Task::where("list_id", $id)
                ->where('deadline', $deadline)
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

            $lista = [];
            foreach ($filter_result as $key => $result) {

                $lista[] = [
                    "title" => $result["title"],
                    "list_id" => $result["list_id"],
                    "deadline_local_timezone" => date('d-m-Y H:i', $result["deadline"]),
                    "deadline_user_timezone" => (new DateTime(date('d-m-Y H:i', $result["deadline"]), new DateTimeZone('UTC')))->setTimezone(new DateTimeZone($usertimezone))->format('d-m-Y H:i'),
                    "done" => $result["done"]
                ];

                
            }
        }else {
            return response([
                "message" => "You are not created this list or this list not exist"
            ]);
        }

        return  $lista;
        
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

    //Edit Task
    public function update(Request $request, $id)
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

        $user_id = Auth::id();

        $user_daily = Daily::where('id', intval($fields['list_id']))->first();
        $user_id_daily = intval($user_daily["user_id"]);

        $task = Task::find($id);
        if ($user_id == $user_id_daily) {
            $task->update($request->all());
            return $task;
        }else {
            return response([
                "message" => "You are not created ". $user_daily["title"]
            ]);
        }
    }

    //Delete Task
    public function destroy($id)
    {
        $delete = Task::destroy($id);
        if ($delete) {
            return response([
                "message" => "Task is deleted sucessfully"
            ]);
        }
    }

    //Change status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'done' => 'required|in:true,false'
        ]);
        $task = Task::find($id)->first();
        if ($task) {
            $task->done = $request->done;
            $task->save();
        }
        return $task;
    }
}
