<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     * @table devices
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('area_id');
            $table->string('name', 255)->nullable()->default(null);
            $table->string('mac', 255);
            $table->string('ip', 255)->nullable()->default(null);
            $table->string('ssid', 40)->nullable()->default(null);
            $table->string('password', 255)->nullable()->default(null);
            $table->integer('rssi_setting')->nullable()->default('0');
            $table->integer('school_id');
            $table->nullableTimestamps();


            $table->foreign('area_id', 'devices_area_id_foreign')
                ->references('id')->on('areas')
                ->onDelete('')
                ->onUpdate('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('devices');
     }
}
