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
        return self::where('user_id', $user_id)->
            orderBy(\DB::raw('ISNULL(completed_at)'), 'DESC')->
            orderBy(\DB::raw('completed_at'), 'DESC')->
            orderByDesc('created_at')->
            get();
    }
}
