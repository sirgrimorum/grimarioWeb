<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEnterprisesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enterprises', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nickname', 30);
			$table->string('fullname', 250);
			$table->double('difficulty')->default(50);
			$table->integer('scale')->default(200);
			$table->text('description')->nullable();
			$table->string('state',3)->default(0);
			$table->string('type',3)->default(0);
			$table->string('logo', 150)->nullable();
			$table->string('url', 80)->nullable();
			$table->string('linkedin', 250)->nullable();
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
		Schema::drop('enterprises');
	}

}
