<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAreasTable extends Migration
{
    /**
     * Run the migrations.
     * @table user_areas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_areas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('user_id');
            $table->bigInteger('area_id');
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
       Schema::dropIfExists('user_areas');
     }
}
