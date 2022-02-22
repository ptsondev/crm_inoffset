<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pid');
            $table->foreignId('uid');
            $table->tinyInteger('status')->default(0);
            $table->string('task', 255);
            $table->string('note_for_me', 1024)->nullable();
            $table->string('my_note', 1024)->nullable();
            $table->dateTime('begin_at')->nullable();
            $table->dateTime('finish_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
