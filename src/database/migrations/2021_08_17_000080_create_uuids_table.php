<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUuidsTable extends Migration
{
    /**
     * Run the migrations.
     * @table uuids
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uuids', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('user_type_id');
            $table->char('uuid', 36);

            $table->unique(["uuid"], 'unique_uuids');


            $table->foreign('user_type_id', 'uuids_user_type_id_foreign')
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
       Schema::dropIfExists('uuids');
     }
}
