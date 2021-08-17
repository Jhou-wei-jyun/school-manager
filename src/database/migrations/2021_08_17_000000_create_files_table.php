<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     * @table files
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('file_id');
            $table->string('path', 255)->nullable()->default(null);
            $table->tinyInteger('status')->default('1');
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
       Schema::dropIfExists('files');
     }
}
