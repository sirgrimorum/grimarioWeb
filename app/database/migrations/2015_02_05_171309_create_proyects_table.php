<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProyectsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('proyects', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('code', 20);
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->text('problem')->nullable();
            $table->text('resources')->nullable();
            $table->text('expectations')->nullable();
            $table->text('experience')->nullable();
            $table->text('satisfaction')->nullable();
            $table->string('pop', 200);
            $table->string('type', 3)->default(0);
            $table->string('priority', 3)->default(0);
            $table->string('state', 3)->default(0);
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('proyects');
    }

}
