<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTypesTable extends Migration
{
    /**
     * Run the migrations.
     * @table feedback_types
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('feedBackType', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('feedback_types');
     }
}
