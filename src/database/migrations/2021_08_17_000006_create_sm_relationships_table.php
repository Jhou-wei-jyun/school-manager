<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     * @table sm_relationships
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_relationships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('school_id')->nullable()->default(null);
            $table->string('mechanical', 255)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('sm_relationships');
     }
}
