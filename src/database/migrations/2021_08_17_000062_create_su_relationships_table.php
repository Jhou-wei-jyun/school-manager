<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     * @table su_relationships
     *
     * @return void
     */
    public function up()
    {
        Schema::create('su_relationships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('school_id')->nullable()->default(null);
            $table->integer('user_id')->nullable()->default(null);
            $table->integer('position_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('su_relationships');
     }
}
