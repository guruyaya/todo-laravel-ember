<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharedItem extends Model
{
    public function user_id()
    {
        return $this->belongsTo(User::class);
    }
    public function todo_item_id() {
        return $this->belongsTo(TodoItem::class);
    }
}
