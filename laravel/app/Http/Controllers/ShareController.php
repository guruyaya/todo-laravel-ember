<?php

namespace App\Http\Controllers;

use App\User;
use App\TodoItem;
use App\SharedItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    function ask_for_share(Request $request){
        if (!Auth::check()) {
            return ['success'=>false, 'error' => 'not logged in'];
        }
        $item_id = $request->item_id;
        $task = TodoItem::
            where('id', $item_id)->
            where('user_id', Auth::id())->
            get();
        if (count($task) == 0) {
            return ['success'=>false, 'error'=>'task must be created by you, if you want to share it'];
        }

        $share_users = User::where('users.id', '!=', Auth::id())->
                            leftJoin('shared_items', function($join) use ($item_id) {
                            $join->on('users.id', '=', 'shared_items.user_id')->
                                where('shared_items.todo_item_id', '=', $item_id);
                        })->orderBy(\DB::raw('ISNULL(shared_items.todo_item_id)'))->
                    get(['Users.name', 'Users.id',
                        \DB::raw('NOT ISNULL(shared_items.todo_item_id) AS is_shared_with')]);
        return ['success'=>true, 'share_users' => $share_users];
    }

    function share_item(Request $request) {
        if (!Auth::check()) {
            return ['success'=>false, 'error' => 'not logged in'];
        }
        $item_id = $request->item_id;
        $task = TodoItem::
            where('id', $item_id)->
            where('user_id', Auth::id())->
            get();
        if (count($task) == 0) {
            return ['success'=>false, 
                'error'=>'task must be created by you, if you want to share it'];
        }
        if (!empty($request->users_to_share)) {
            $users = explode(',', $request->users_to_share);
            foreach ($users as $user) {
                if (!is_numeric($user)) {
                    return ['success'=>false, 'error'=> $user . ' is not numeric. Report site'];
                }
            }
        }

        SharedItem::where('todo_item_id', $item_id)->delete();

        if (!empty($request->users_to_share)) {
            foreach ($users as $user) {
                # TODO see why timestamps don't resolve automatically.
                SharedItem::insert(['todo_item_id' => $item_id, 'user_id' => $user]);
            }
        }
        $todo_items = TodoItem::get_user_tasks(Auth::id());
        $share_users = User::where('users.id', '!=', Auth::id())->
                            leftJoin('shared_items', function($join) use ($item_id) {
                            $join->on('users.id', '=', 'shared_items.user_id')->
                                where('shared_items.todo_item_id', '=', $item_id);
                        })->orderBy(\DB::raw('ISNULL(shared_items.todo_item_id)'))->
                    get(['Users.name', 'Users.id',
                        \DB::raw('NOT ISNULL(shared_items.todo_item_id) AS is_shared_with')]);
        return ['success'=>true, 'share_users' => $share_users,
            'todo_items' => $todo_items];
    }
}
