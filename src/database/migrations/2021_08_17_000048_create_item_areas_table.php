<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAreasTable extends Migration
{
    /**
     * Run the migrations.
     * @table item_areas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_areas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('item_id');
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
       Schema::dropIfExists('item_areas');
     }
}
