<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPageTable extends Migration
{
    /**
     * Run the migrations.
     * @table group_page
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_page', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('group_id');
            $table->bigInteger('page_id');
            $table->tinyInteger('show')->default('0');


            $table->foreign('group_id', 'group_page_group_id_foreign')
                ->references('group_id')->on('groups')
                ->onDelete('')
                ->onUpdate('');

            $table->foreign('page_id', 'group_page_page_id_foreign')
                ->references('page_id')->on('pages')
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
       Schema::dropIfExists('group_page');
     }
}
