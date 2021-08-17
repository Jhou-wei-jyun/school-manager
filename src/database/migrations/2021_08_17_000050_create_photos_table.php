<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     * @table photos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('photo_id');
            $table->string('path', 255)->nullable()->default(null);
            $table->bigInteger('medicine_id')->nullable()->default(null);
            $table->bigInteger('album_id')->nullable()->default(null);
            $table->tinyInteger('status')->nullable()->default('1');
            $table->bigInteger('imageable_id');
            $table->string('imageable_type', 100);
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
       Schema::dropIfExists('photos');
     }
}
