<?php

namespace App\Http\Controllers;

use App\User;
use App\TodoItem;
use App\SharedItem;

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
    public function delete_shared_item($item_id) {
        // Deleting an item shared with you, removes it from your list
        // but not from author list
        $share_query = SharedItem::
            where('todo_item_id', $item_id)->
            where('user_id', Auth::id());
        $affected = $share_query->delete();

        $todo_items = TodoItem::get_user_tasks(Auth::id());
        if ($affected == 0){ // this means the task wasn't shared with you.
            return ['success'=>false, 'error'=>'this is not your task', 'todo_items'=>$todo_items];
        }
        return ['success'=>true, 'todo_items'=>$todo_items];

    }
    public function delete_item(Request $request) {
        if (!Auth::check()){
            return ['success'=>false, 'error' => 'not logged in'];
        }
        $item_id = $request->item_id;
        $task_query = TodoItem::where('id', $item_id);
        $task = $task_query->first();

        if ($task['user_id'] != Auth::id()) {
            return $this->delete_shared_item($item_id);
        }

        $success = $task_query->delete();

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
        $task_query = TodoItem::
            where('id', $data['task_id'])->
            where('user_id', Auth::id());
        $task = $task_query->get();

        if (count($task) == 0) {
            $task_query = SharedItem::
                where('todo_item_id', $data['task_id'])->
                where('user_id', Auth::id());
            $task = $task_query->get();
            if (count($task) == 0) {
                $todo_items = TodoItem::get_user_tasks(Auth::id());
                return ['success'=>false, 'error'=>'this is not your task', 'todo_items'=>$todo_items];
            }
        }

        if ($data['is_done'] == 'true'){
            $affected = TodoItem::
            where('id', $data['task_id'])->
            update([
                'completed_at' => \DB::raw('NOW()'), 
                'completed_by' => Auth::id()
            ]);
        }else{
            $affected = TodoItem::
            where('id', $data['task_id'])->
            update([
                'completed_at' => null,
                'completed_by' => null
            ]);
        }
        $todo_items = TodoItem::get_user_tasks(Auth::id());
        return ['success' => true, 'todo_items'=>$todo_items];
    }
}
