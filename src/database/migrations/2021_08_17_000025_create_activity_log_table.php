<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     * @table activity_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('log_name', 255)->nullable()->default(null);
            $table->text('description');
            $table->bigInteger('subject_id')->nullable()->default(null);
            $table->string('subject_type', 255)->nullable()->default(null);
            $table->bigInteger('causer_id')->nullable()->default(null);
            $table->string('causer_type', 255)->nullable()->default(null);
            $table->json('properties')->nullable()->default(null);
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
       Schema::dropIfExists('activity_log');
     }
}
