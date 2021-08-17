<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupBlockTable extends Migration
{
    /**
     * Run the migrations.
     * @table group_block
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_block', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('group_id');
            $table->bigInteger('block_id');
            $table->tinyInteger('show')->default('0');


            $table->foreign('block_id', 'group_block_block_id_foreign')
                ->references('block_id')->on('blocks')
                ->onDelete('')
                ->onUpdate('');

            $table->foreign('group_id', 'group_block_group_id_foreign')
                ->references('group_id')->on('groups')
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
       Schema::dropIfExists('group_block');
     }
}
