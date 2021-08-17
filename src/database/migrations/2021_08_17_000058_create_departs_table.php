<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartsTable extends Migration
{
    /**
     * Run the migrations.
     * @table departs
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('depart_id');
            $table->string('name', 255)->nullable()->default(null);
            $table->integer('school_id')->nullable()->default(null);
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
       Schema::dropIfExists('departs');
     }
}
