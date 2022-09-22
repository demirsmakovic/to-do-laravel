<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //Show All Tasks
    public function index()
    {
        return Task::all();
    }

    //Create Task
    public function store(Request $request)
    {
        $fields = $request->validate([
                'list_id' => 'required|exists:dailies,id',
                'title' => 'required',
                'description' => 'required',
                'deadline' => 'required|date_format:d-m-Y|after:yesterday',
                'done' => 'required|boolean'
            ],
            [
                'list_id.exists' => 'Daily list with this id not exists'
            ]
            );

        $task = Task::create([
            'list_id' => $fields['list_id'],
            'title' => $fields['title'],
            'description' => $fields['description'],
            'deadline' => $fields['deadline'],
            'done' => $fields['done']
        ]);

        $response = [
            'task' => $task
        ];

        return response($response);
    }
}
