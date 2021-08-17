<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     * @table jobs
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('queue', 255);
            $table->longText('payload');
            $table->tinyInteger('attempts');
            $table->integer('reserved_at')->unsigned()->nullable()->default(null);
            $table->integer('available_at')->unsigned();
            $table->integer('created_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('jobs');
     }
}
