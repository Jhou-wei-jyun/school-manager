<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     * @table areas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('photo', 255)->nullable()->default(null);
            $table->string('lottie', 255)->nullable()->default(null);
            $table->string('location_photo', 255)->nullable()->default(null);
            $table->string('location_photo_social_0', 255)->nullable()->default(null);
            $table->string('location_photo_social_1', 255)->nullable()->default(null);
            $table->string('location_photo_social_2', 255)->nullable()->default(null);
            $table->string('location_emergency_exit', 255)->nullable()->default(null);
            $table->integer('num_devices')->default('1');
            $table->integer('max_num_peoples')->default('0');
            $table->integer('num_peoples')->default('0');
            $table->bigInteger('area_statu_id')->nullable()->default(null);
            $table->integer('school_id');
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
       Schema::dropIfExists('areas');
     }
}
