<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     * @table items
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('mac', 255);
            $table->string('name', 255);
            $table->bigInteger('supervisor_id')->default('1');
            $table->string('photo', 255)->nullable()->default(null);
            $table->text('details')->nullable()->default(null);
            $table->bigInteger('level_id');
            $table->tinyInteger('active')->default('1');

            $table->unique(["mac"], 'unique_items');
            $table->nullableTimestamps();


            $table->foreign('level_id', 'items_level_id_foreign')
                ->references('id')->on('levels')
                ->onDelete('')
                ->onUpdate('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('items');
     }
}
