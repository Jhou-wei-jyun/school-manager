<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTaggablesTable extends Migration
{
    /**
     * Run the migrations.
     * @table user_taggables
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_taggables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('user_id');
            $table->bigInteger('user_taggable_id');
            $table->string('user_taggable_type', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('user_taggables');
     }
}
