<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     * @table achievements
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('achievement_id');
            $table->bigInteger('user_id');
            $table->tinyInteger('returnContact')->nullable()->default('0');
            $table->tinyInteger('goToSchoolOnTime')->nullable()->default('0');
            $table->tinyInteger('getTemperature')->nullable()->default('0');
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
       Schema::dropIfExists('achievements');
     }
}
