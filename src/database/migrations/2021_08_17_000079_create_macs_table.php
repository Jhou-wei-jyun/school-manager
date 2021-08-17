<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMacsTable extends Migration
{
    /**
     * Run the migrations.
     * @table macs
     *
     * @return void
     */
    public function up()
    {
        Schema::create('macs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('user_type_id');
            $table->char('mac', 36);

            $table->unique(["mac"], 'unique_macs');


            $table->foreign('user_type_id', 'macs_user_type_id_foreign')
                ->references('id')->on('user_type')
                ->onDelete('cascade')
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
       Schema::dropIfExists('macs');
     }
}
