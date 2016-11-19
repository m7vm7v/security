<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFingerprintsTable extends Migration
{
    /**'' ,'', '', '', '', '', '' , '' , ''
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_fingerprints', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('user_id');
            $table->string('ip');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('lang')->nullable();
            $table->string('platform')->nullable();
            $table->string('agent')->nullable();
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
        Schema::dropIfExists('user_fingerprints');
    }
}
