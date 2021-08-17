<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     * @table face_records
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('temperature_val', 255);
            $table->string('recognition_name', 255);
            $table->bigInteger('face_user_id')->nullable()->default(null);
            $table->dateTime('record_time');
            $table->bigInteger('statu_id')->nullable()->default(null);
            $table->string('equipment_verification_id', 255)->nullable()->default(null);
            $table->bigInteger('school_id')->nullable()->default(null);
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
       Schema::dropIfExists('face_records');
     }
}
