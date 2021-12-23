<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('shifts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('attendance_id');
      $table->unsignedBigInteger('before_shift_id')->nullable();
      $table->integer('is_edit');
      $table->integer('item');
      $table->time('start_at')->nullable();
      $table->time('end_at')->nullable();
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
    Schema::dropIfExists('shifts');
  }
}
