<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentTaskTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_task', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payment_id')->unsigned()->index();
			$table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
			$table->integer('task_id')->unsigned()->index();
			$table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
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
		Schema::drop('payment_task');
	}

}
