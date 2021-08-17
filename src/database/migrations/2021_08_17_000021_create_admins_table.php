<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     * @table admins
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('account', 255)->nullable()->default(null);
            $table->string('password', 255)->nullable()->default('smartcube');
            $table->string('name', 255)->nullable()->default(null);
            $table->integer('school_id')->nullable()->default(null);
            $table->string('api_token', 255)->nullable()->default(null);
            $table->integer('level_id')->nullable()->default('1');
            $table->integer('group_id')->nullable()->default('1');
            $table->integer('user_id')->nullable()->default(null);
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
       Schema::dropIfExists('admins');
     }
}
