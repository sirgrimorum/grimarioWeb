<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 50);
			$table->integer('percentage');
			$table->double('value');
			$table->double('plan');
			$table->double('planh');
			$table->text('conditions')->nullable();
			$table->date('plandate');
			$table->date('paymentdate');
			$table->string('state', 3)->default('0');
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
		Schema::drop('payments');
	}

}
