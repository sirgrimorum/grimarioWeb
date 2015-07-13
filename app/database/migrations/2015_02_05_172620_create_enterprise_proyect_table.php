<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEnterpriseProyectTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enterprise_proyect', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('enterprise_id')->unsigned()->index();
			$table->foreign('enterprise_id')->references('id')->on('enterprises')->onDelete('cascade');
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
	public function down()
	{
		Schema::drop('enterprice_proyect');
	}

}
