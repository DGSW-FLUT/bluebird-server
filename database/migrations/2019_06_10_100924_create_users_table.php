<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('birth');
            $table->string('zip_code');
            $table->string('address');
            $table->string('job');
            $table->string('level');
            $table->string('phone_number');
            $table->string('education');
            $table->string('gender');
            $table->integer('attendance_fee');
            $table->string('agree');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
