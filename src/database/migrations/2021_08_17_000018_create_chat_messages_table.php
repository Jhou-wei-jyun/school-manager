<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     * @table chat_messages
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->timestamp('date')->nullable()->default(null);
            $table->string('from', 50)->nullable()->default(null);
            $table->string('to', 50)->nullable()->default(null);
            $table->string('message', 200)->nullable()->default(null);
            $table->string('status', 1)->nullable()->default(null);
            $table->string('school_id', 11)->nullable()->default(null);
            $table->string('parent_id', 11)->nullable()->default(null);
            $table->string('user_id', 11)->nullable()->default(null);
            $table->string('identity', 100)->nullable()->default(null);
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
       Schema::dropIfExists('chat_messages');
     }
}
