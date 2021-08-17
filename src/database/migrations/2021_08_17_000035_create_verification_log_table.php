<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationLogTable extends Migration
{
    /**
     * Run the migrations.
     * @table verification_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('phone', 10);
            $table->string('api', 100);
            $table->tinyInteger('sent');
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
       Schema::dropIfExists('verification_log');
     }
}
