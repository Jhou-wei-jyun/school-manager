<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSddRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     * @table sdd_relationships
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdd_relationships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('school_id')->nullable()->default(null);
            $table->integer('depart_id')->nullable()->default(null);
            $table->integer('department_id')->nullable()->default(null);
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
       Schema::dropIfExists('sdd_relationships');
     }
}
