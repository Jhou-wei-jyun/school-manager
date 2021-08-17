<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @table user_users
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('user_id');
            $table->bigInteger('users_user_id');
            $table->nullableTimestamps();


            $table->foreign('user_id', 'user_users_user_id_foreign')
                ->references('id')->on('users')
                ->onDelete('')
                ->onUpdate('');

            $table->foreign('users_user_id', 'user_users_users_user_id_foreign')
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
       Schema::dropIfExists('user_users');
     }
}
