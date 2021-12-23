<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyShiftsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::table('shifts', function (Blueprint $table) {
      $table->foreign('attendance_id')
      ->references('id')
      ->on('attendances')
      ->onUpdate('cascade')
      ->onDelete('cascade');
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::table('shifts', function (Blueprint $table) {
      $table->dropForeign(['attendance_id']);
    });
  }
}
