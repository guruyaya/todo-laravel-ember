<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('todo_item_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['todo_item_id', 'user_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('todo_item_id')->references('id')->on('todo_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_items');
    }
}
