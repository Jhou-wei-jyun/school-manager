<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpfRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     * @table spf_relationships
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spf_relationships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('school_id')->nullable()->default(null);
            $table->integer('parent_id')->nullable()->default(null);
            $table->integer('face_user_id')->nullable()->default(null);
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
       Schema::dropIfExists('spf_relationships');
     }
}
