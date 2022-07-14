<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcrmClientsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('acrm_clients', function (Blueprint $table) {
			$table->id();
			$table->string('accessToken', 1500);
			$table->string('refreshToken', 1500);
			$table->unsignedBigInteger('expires');
			$table->string('baseDomain');
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
		Schema::dropIfExists('acrm_clients');
	}
}
