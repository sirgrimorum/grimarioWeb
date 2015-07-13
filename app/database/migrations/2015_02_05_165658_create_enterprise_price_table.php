<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEnterprisePriceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enterprise_price', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('enterprise_id')->unsigned()->index();
			$table->foreign('enterprise_id')->references('id')->on('enterprises')->onDelete('cascade');
			$table->integer('price_id')->unsigned()->index();
			$table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
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
		Schema::drop('enterprice_price');
	}

}
