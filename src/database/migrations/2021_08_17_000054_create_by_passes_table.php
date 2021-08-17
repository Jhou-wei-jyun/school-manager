<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateByPassesTable extends Migration
{
    /**
     * Run the migrations.
     * @table by_passes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('by_passes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('phone', 10)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('by_passes');
     }
}
