<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemperaturesTable extends Migration
{
    /**
     * Run the migrations.
     * @table temperatures
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temperatures', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('temperature_val', 255);
            $table->string('recognition_name', 255)->nullable()->default(null);
            $table->bigInteger('user_id')->nullable()->default(null);
            $table->string('record_time', 255);
            $table->bigInteger('statu_id')->nullable()->default(null);
            $table->string('equipment_verification_id', 100);
            $table->integer('school_id')->nullable()->default(null);
            $table->tinyInteger('check')->nullable()->default('1');
            $table->nullableTimestamps();


            $table->foreign('statu_id', 'temperatures_statu_id_foreign')
                ->references('id')->on('status')
                ->onDelete('')
                ->onUpdate('');

            $table->foreign('user_id', 'temperatures_user_id_foreign')
                ->references('id')->on('users')
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
       Schema::dropIfExists('temperatures');
     }
}
