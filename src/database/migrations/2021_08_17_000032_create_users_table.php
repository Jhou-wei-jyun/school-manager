<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('device_token', 255)->nullable()->default(null);
            $table->string('api_token', 255)->nullable()->default(null);
            $table->bigInteger('level_id')->nullable()->default('0');
            $table->bigInteger('department_id')->nullable()->default(null);
            $table->bigInteger('position_id');
            $table->date('onboard_date')->default('2020-06-17');
            $table->tinyInteger('is_actived')->default('1');
            $table->integer('school_id')->nullable()->default(null);
            $table->string('phone', 10)->nullable()->default(null);
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
       Schema::dropIfExists('users');
     }
}
