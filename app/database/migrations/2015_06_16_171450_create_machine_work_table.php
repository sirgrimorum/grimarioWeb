<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMachineWorkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('machine_work', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('machine_id')->unsigned()->index();
			$table->foreign('machine_id')->references('id')->on('machines')->onDelete('cascade');
			$table->integer('work_id')->unsigned()->index();
			$table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
                        $table->double('hours');
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
		Schema::drop('machine_work');
	}

}
