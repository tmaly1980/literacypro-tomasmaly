<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('albums', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('band_id')->unsigned();
            $table->string('name');
            $table->date('recorded_date');
            $table->date('release_date');
            $table->integer('number_of_tracks')->unsigned();
            $table->string('label');
            $table->string('producer');
            $table->string('genre');
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
		Schema::drop('albums');
	}

}
