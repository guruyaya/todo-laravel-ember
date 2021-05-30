<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    public function list(Request $request) {
        if (!Auth::check()){
            return ['success'=>false];
        }
        $todo_items = \App\TodoItem::get_user_tasks(Auth::id());
        return ['success'=>true, 'todo_items'=>$todo_items];
    }

    public function set_completed(Request $request) {
        if (!Auth::check()){
            return ['success'=>false, 'error'=>'not logged in'];
        }

        $data = $request->post();
        $task_query = \App\TodoItem::
            where('id', $data['task_id'])->
            where('user_id', Auth::id());
        $task = $task_query->get();

        if (count($task) == 0) {
            $todo_items = \App\TodoItem::get_user_tasks(Auth::id());
            return ['success'=>false, 'error'=>'this is not your task', 'todo_items'=>$todo_items];
        }

        if ($data['is_done'] == 'true'){
            $affected = $task_query->update([
                'completed_at' => \DB::raw('NOW()'), 
                'completed_by' => Auth::id()
            ]);
        }else{
            $affected = $task_query->update([
                'completed_at' => null,
                'completed_by' => null
            ]);
        }
        $todo_items = \App\TodoItem::get_user_tasks(Auth::id());
        return ['success' => true, 'todo_items'=>$todo_items];
    }
}
