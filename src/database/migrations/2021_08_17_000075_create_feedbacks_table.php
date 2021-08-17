<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     * @table feedbacks
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('feedback', 100);
            $table->bigInteger('feedback_type_id')->nullable()->default(null);
            $table->bigInteger('userable_id');
            $table->string('userable_type', 100);
            $table->nullableTimestamps();


            $table->foreign('feedback_type_id', 'feedbacks_FK')
                ->references('id')->on('feedback_types')
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
       Schema::dropIfExists('feedbacks');
     }
}
