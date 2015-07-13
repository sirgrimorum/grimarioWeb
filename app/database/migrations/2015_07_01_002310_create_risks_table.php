<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRisksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('risks', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->text('description');
            $table->string('type', 3);
            $table->integer('probability');
            $table->string('impact', 3);
            $table->string('importance', 3);
            $table->boolean('detect');
            $table->string('state', 3);
            $table->date('date');
            $table->text('trigger');
            $table->text('response');
            $table->text('plan');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('payment_id')->unsigned()->index();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('risks');
    }

}
