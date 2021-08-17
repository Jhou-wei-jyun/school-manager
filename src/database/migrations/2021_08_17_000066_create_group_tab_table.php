<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTabTable extends Migration
{
    /**
     * Run the migrations.
     * @table group_tab
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_tab', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('group_id');
            $table->bigInteger('tab_id');
            $table->tinyInteger('show')->default('0');


            $table->foreign('group_id', 'group_tab_group_id_foreign')
                ->references('group_id')->on('groups')
                ->onDelete('')
                ->onUpdate('');

            $table->foreign('tab_id', 'group_tab_tab_id_foreign')
                ->references('tab_id')->on('tabs')
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
       Schema::dropIfExists('group_tab');
     }
}
