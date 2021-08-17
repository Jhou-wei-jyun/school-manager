<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesNewTable extends Migration
{
    /**
     * Run the migrations.
     * @table chat_messages_new
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages_new', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('teacher_id');
            $table->integer('parent_id');
            $table->string('identity', 50);
            $table->string('message', 200);
            $table->integer('status');
            $table->integer('user_id');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('chat_messages_new');
     }
}
