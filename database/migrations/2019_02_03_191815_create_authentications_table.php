<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('failed_login_attempts');
        Schema::create('authentications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->ipAddress('ip_address');
            $table->string('type')->default('login')->index();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('device_id')->unsigned()->index()->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authentications');
    }
}
