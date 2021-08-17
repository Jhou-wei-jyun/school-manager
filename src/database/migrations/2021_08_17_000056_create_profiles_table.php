<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     * @table profiles
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('profile_id');
            $table->integer('user_id')->nullable()->default(null);
            $table->string('name', 255)->nullable()->default(null);
            $table->longText('avatar')->nullable()->default(null);
            $table->integer('gender')->nullable()->default(null);
            $table->date('birthday')->nullable()->default(null);
            $table->string('nickname', 255)->nullable()->default(null);
            $table->string('height', 100)->nullable()->default(null);
            $table->string('weight', 100)->nullable()->default(null);
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
       Schema::dropIfExists('profiles');
     }
}
