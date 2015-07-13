<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIndicatorsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('indicators', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->text('description');
            $table->string('type', 3);
            $table->date('date');
            $table->string('fuente', 150);
            $table->text('musthave');
            $table->text('nicetohave');
            $table->text('ideal');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('payment_id')->unsigned()->index();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->string('priority', 3);
            $table->string('state', 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('indicators');
    }

}
