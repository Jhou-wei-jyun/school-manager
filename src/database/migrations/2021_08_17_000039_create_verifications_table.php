<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     * @table verifications
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('phone', 10);
            $table->string('verification_num', 4)->nullable()->default(null);
            $table->timestamp('login_date')->nullable()->default(null);
            $table->string('send_flg', 5)->nullable()->default(null);
            $table->integer('login_count')->nullable()->default('0');
            $table->string('token', 100)->nullable()->default(null);
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
       Schema::dropIfExists('verifications');
     }
}
