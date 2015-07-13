<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50);
			$table->string('code', 20);
			$table->text('result');
			$table->text('description')->nullable();
			$table->integer('type')->unsigned()->index();
			$table->string('state',3)->default(0);
			$table->dateTime('plan');
			$table->double('expenses')->default(0);
			$table->double('difficulty')->default(2);
			$table->dateTime('start');
			$table->dateTime('end');
			$table->string('satisfaction',3)->default(0);
			$table->string('cuality',3)->default(0);
			$table->integer('order')->default(0);
			$table->integer('percentage')->default(0);
			$table->integer('dpercentage')->default(0);
			$table->double('pcuantity');
			$table->double('dcuantity');
			$table->integer('proyect_id')->unsigned()->index();
			$table->foreign('proyect_id')->references('id')->on('proyects')->onDelete('cascade');
			$table->integer('game_id')->unsigned()->index();
			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
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
		Schema::drop('tasks');
	}

}
