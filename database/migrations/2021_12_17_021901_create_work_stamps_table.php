<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkStampsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('work_stamps', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('attendance_id');
      $table->unsignedBigInteger('before_work_stamp_id')->nullable();
      $table->integer('is_edit');
      $table->integer('is_start');
      $table->time('stamp_at')->nullable();
      $table->integer('exception')->default(0);
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
    Schema::dropIfExists('work_stamps');
  }
}
