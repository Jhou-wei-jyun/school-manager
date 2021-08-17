<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     * @table parents
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('parent_id');
            $table->string('name', 100)->nullable()->default(null);
            $table->string('ename', 100)->nullable()->default(null);
            $table->string('phone', 10)->nullable()->default(null);
            $table->string('title', 100)->nullable()->default(null);
            $table->integer('school_id')->nullable()->default(null);
            $table->string('device_token', 255)->nullable()->default(null);
            $table->string('api_token', 255)->nullable()->default(null);
            $table->string('system', 100)->nullable()->default(null);
            $table->string('address', 255)->nullable()->default(null);
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
       Schema::dropIfExists('parents');
     }
}
