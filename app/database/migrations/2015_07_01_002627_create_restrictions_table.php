<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestrictionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('restrictions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->text('description');
            $table->string('type', 3);
            $table->string('state', 3);
            $table->date('date');
            $table->text('comments');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('proyect_id')->unsigned()->index();
            $table->foreign('proyect_id')->references('id')->on('proyects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('restrictions');
    }

}
