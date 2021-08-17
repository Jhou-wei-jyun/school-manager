<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @table face_users
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('account', 255)->nullable()->default(null);
            $table->string('password', 255)->default('smartcube');
            $table->char('uuid', 36);
            $table->string('device_token', 255)->default('register from HR');
            $table->string('system', 255)->default('ios');
            $table->string('api_token', 255)->default('register from HR');
            $table->string('name', 255);
            $table->integer('gender');
            $table->longText('avatar');
            $table->bigInteger('level_id')->default('0');
            $table->bigInteger('department_id')->default('0');
            $table->bigInteger('position_id')->default('10');
            $table->date('onboard_date');
            $table->date('birthday')->nullable()->default(null);
            $table->tinyInteger('is_actived')->default('1');
            $table->integer('school_id');
            $table->char('phone', 10)->nullable()->default(null);
            $table->string('mac', 11)->nullable()->default(null);
            $table->string('nick_name', 255)->nullable()->default(null);

            $table->unique(["uuid"], 'unique_face_users');
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
       Schema::dropIfExists('face_users');
     }
}
