<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     * @table user_notifies
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('user_id');
            $table->bigInteger('notify_id');
            $table->integer('status')->nullable()->default('0');
            $table->integer('student_id')->nullable()->default(null);
            $table->nullableTimestamps();


            $table->foreign('notify_id', 'user_notifies_notify_id_foreign')
                ->references('id')->on('notifies')
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
       Schema::dropIfExists('user_notifies');
     }
}
