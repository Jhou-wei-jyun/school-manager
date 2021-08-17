<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOriRecordsTable extends Migration
{
    /**
     * Run the migrations.
     * @table ori_records
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ori_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('tag', 255);
            $table->string('mac', 255);
            $table->string('date_time', 255);
            $table->string('date_long', 255);
            $table->integer('rssi');
            $table->integer('area_id');
            $table->integer('bat');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('ori_records');
     }
}
