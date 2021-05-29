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
        $todo_items = \App\TodoItem::where('user_id', Auth::id())->
            orderBy(\DB::raw('ISNULL(completed_at)'), 'DESC')->
            orderByDesc('created_at')->
            get();

        return ['success'=>true, 'todo_items'=>$todo_items];
    }
}
