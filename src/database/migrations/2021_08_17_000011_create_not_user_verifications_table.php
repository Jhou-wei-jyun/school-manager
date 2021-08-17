<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotUserVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     * @table not_user_verifications
     *
     * @return void
     */
    public function up()
    {
        Schema::create('not_user_verifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('phone', 10);
            $table->string('verification_num', 4)->nullable()->default(null);
            $table->string('send_flg', 5)->nullable()->default(null);
            $table->integer('count')->nullable()->default('0');
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
       Schema::dropIfExists('not_user_verifications');
     }
}
