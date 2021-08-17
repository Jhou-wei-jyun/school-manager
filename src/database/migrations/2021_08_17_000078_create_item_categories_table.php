<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * @table item_categories
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('item_id');
            $table->bigInteger('category_id');
            $table->nullableTimestamps();


            $table->foreign('category_id', 'item_categories_category_id_foreign')
                ->references('id')->on('categories')
                ->onDelete('')
                ->onUpdate('');

            $table->foreign('item_id', 'item_categories_item_id_foreign')
                ->references('id')->on('items')
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
       Schema::dropIfExists('item_categories');
     }
}
