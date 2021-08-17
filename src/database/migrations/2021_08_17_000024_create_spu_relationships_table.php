<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpuRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     * @table spu_relationships
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spu_relationships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('school_id')->nullable()->default(null);
            $table->integer('parent_id')->nullable()->default(null);
            $table->integer('user_id')->nullable()->default(null);
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
       Schema::dropIfExists('spu_relationships');
     }
}
