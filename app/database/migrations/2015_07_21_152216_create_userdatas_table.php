<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserdatasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('userdatas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('charge', 150);
			$table->string('oenterprise', 150);
			$table->string('cel', 20);
			$table->string('otel', 20);
			$table->text('dir');
			$table->text('comments');
                        $table->integer('user_id')->unsigned()->index();
                        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('userdatas');
	}

}
