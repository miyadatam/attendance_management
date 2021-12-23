<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('overtimes', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('attendance_id');
      $table->unsignedBigInteger('before_overtime_id')->nullable();
      $table->integer('is_early');
      $table->time('start_at');
      $table->time('end_at');
      $table->integer('approve')->comment('承認状況');
      $table->string('memo')->nullable();
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
    Schema::dropIfExists('overtimes');
  }
}
