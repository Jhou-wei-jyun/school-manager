<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     * @table user_examinations
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_examinations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('user_id');
            $table->bigInteger('examination_id');
            $table->integer('score')->default('0');
            $table->nullableTimestamps();


            $table->foreign('examination_id', 'user_examinations_examination_id_foreign')
                ->references('id')->on('examinations')
                ->onDelete('')
                ->onUpdate('');

            $table->foreign('user_id', 'user_examinations_user_id_foreign')
                ->references('id')->on('users')
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
       Schema::dropIfExists('user_examinations');
     }
}
