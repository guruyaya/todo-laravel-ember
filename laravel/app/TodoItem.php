<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
}
