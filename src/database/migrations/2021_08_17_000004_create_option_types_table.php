<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionTypesTable extends Migration
{
    /**
     * Run the migrations.
     * @table option_types
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('option_type_id');
            $table->string('value', 255);
            $table->string('type', 100);
            $table->json('default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('option_types');
     }
}
