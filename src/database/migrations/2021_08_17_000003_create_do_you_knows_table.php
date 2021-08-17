<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoYouKnowsTable extends Migration
{
    /**
     * Run the migrations.
     * @table do_you_knows
     *
     * @return void
     */
    public function up()
    {
        Schema::create('do_you_knows', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('message', 255);
            $table->tinyInteger('active')->default('1');
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
       Schema::dropIfExists('do_you_knows');
     }
}
