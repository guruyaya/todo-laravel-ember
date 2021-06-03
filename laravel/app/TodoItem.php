<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
    public static function get_user_tasks(int $user_id) {
        return self::leftJoin('shared_items', 'todo_items.id', '=', 'shared_items.todo_item_id')->
            groupBy(['todo_items.id', 'todo_items.task'])->
            where('todo_items.user_id', $user_id)->
            orderBy(\DB::raw('ISNULL(completed_at)'), 'DESC')->
            orderBy('todo_items.completed_at', 'DESC')->
            orderBy('todo_items.created_at', 'DESC')->
            get(['todo_items.id', 'todo_items.task', 
                \DB::raw('COUNT(shared_items.todo_item_id) AS share_count')]);
    }
}
