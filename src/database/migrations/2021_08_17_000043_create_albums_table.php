<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     * @table albums
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('album_id');
            $table->string('albumTitle', 250);
            $table->date('albumDate');
            $table->string('albumImage', 250)->nullable()->default(null);
            $table->integer('albumParent')->default('0');
            $table->integer('department_id');
            $table->tinyInteger('status')->nullable()->default('1');
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
       Schema::dropIfExists('albums');
     }
}
