<?php

namespace App\Http\Controllers;

use App\User;
use App\TodoItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    public function list(Request $request) {
        if (!Auth::check()){
            return ['success'=>false, 'error' => 'not logged in'];
        }
        $todo_items = TodoItem::get_user_tasks(Auth::id());
        return ['success'=>true, 'todo_items'=>$todo_items];
    }

    public function delete_item(Request $request) {
        if (!Auth::check()){
            return ['success'=>false, 'error' => 'not logged in'];
        }
        $data = $request->post();
        $task_query = TodoItem::where('id', $data['item_id'])->
            where('user_id', Auth::id());
        $task = $task_query->get();

        if (count($task) == 0) {
            $todo_items = TodoItem::get_user_tasks(Auth::id());
            return ['success'=>false, 'error'=>'this is not your task', 'todo_items'=>$todo_items];
        }

        $success = TodoItem::where(['id' => $data['item_id']])->
            delete();

        $todo_items = TodoItem::get_user_tasks(Auth::id());
        if (!$success){
            return ['success'=>false, 'error' => 'Failed to delte item', 'todo_items' => $todo_items];
        }

        return ['success'=>true, 'todo_items' => $todo_items];
    }

    public function new_item(Request $request) {
        if (!Auth::check()){
            return ['success'=>false, 'error' => 'not logged in'];
        }
        $data = $request->post();
        $item_id = TodoItem::insert([
            'task' => $data['task'],
            'user_id' => Auth::id()
        ]);

        $todo_items = TodoItem::get_user_tasks(Auth::id());
        if (!$item_id){
            return ['success'=>false, 'error' => 'not logged in', 'todo_items' => $todo_items];
        }
        return ['success'=>true, 'todo_items' => $todo_items];
    }

    public function set_completed(Request $request) {
        if (!Auth::check()){
            return ['success'=>false, 'error'=>'not logged in'];
        }

        $data = $request->post();
        $task_query = TodoItem::where('id', $data['task_id'])->
            where('user_id', Auth::id());
        $task = $task_query->get();

        if (count($task) == 0) {
            $todo_items = TodoItem::get_user_tasks(Auth::id());
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
        $todo_items = TodoItem::get_user_tasks(Auth::id());
        return ['success' => true, 'todo_items'=>$todo_items];
    }
}
