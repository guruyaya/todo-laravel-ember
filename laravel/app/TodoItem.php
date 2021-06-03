<?php

namespace App;

use App\SharedItem;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
    public static function get_user_tasks(int $user_id) {
        return self::leftJoin('shared_items', 'todo_items.id', '=', 'shared_items.todo_item_id')->
            groupBy(['todo_items.id', 'todo_items.task', 'todo_items.completed_at', 'todo_items.user_id'])->
            where('todo_items.user_id', $user_id)->
            orWhere('shared_items.user_id', $user_id)->
            orderBy(\DB::raw('ISNULL(completed_at)'), 'DESC')->
            orderBy('todo_items.completed_at', 'DESC')->
            orderBy('todo_items.created_at', 'DESC')->
            get(['todo_items.id', 'todo_items.task', 'todo_items.completed_at',
                \DB::raw('todo_items.user_id = ' . $user_id . ' AS is_mine'),
                \DB::raw('COUNT(shared_items.todo_item_id) AS share_count')]);
    }
}
