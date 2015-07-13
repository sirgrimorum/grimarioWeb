<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommenttypeTasktypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commenttype_tasktype', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('commenttype_id')->unsigned()->index();
			$table->foreign('commenttype_id')->references('id')->on('commenttypes')->onDelete('cascade');
			$table->integer('tasktype_id')->unsigned()->index();
			$table->foreign('tasktype_id')->references('id')->on('tasktypes')->onDelete('cascade');
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
		Schema::drop('commenttype_tasktype');
	}

}
