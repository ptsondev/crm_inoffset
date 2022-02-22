<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255)->defaut('.');
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();

            $table->tinyInteger('source')->default(4);
            $table->tinyInteger('status')->default(1);
            $table->text('description')->nullable()->default('');
            $table->foreignId('assigned')->default(1);
            $table->tinyInteger('priority')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('note')->nullable();
            $table->integer('quotation_price')->nullable();
            $table->foreignId('sale_id')->default(1);
            $table->string('delivery_info', 1024)->default('');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
