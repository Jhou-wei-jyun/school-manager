<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarTable extends Migration
{
    /**
     * Run the migrations.
     * @table avatar
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatar', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('avatar_id');
            $table->integer('profile_id')->nullable()->default(null);
            $table->integer('uuid-id')->nullable()->default(null);
            $table->string('avatar', 255)->nullable()->default(null);
            $table->tinyInteger('sub')->nullable()->default(null);
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
       Schema::dropIfExists('avatar');
     }
}
