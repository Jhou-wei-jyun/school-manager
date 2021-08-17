<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     * @table departments
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('photo', 255)->nullable()->default(null);
            $table->string('start_at', 255);
            $table->string('finish_at', 255);
            $table->bigInteger('supervisor_id')->default('1');
            $table->integer('school_id')->nullable()->default(null);
            $table->longText('avatar')->nullable()->default(null);
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
       Schema::dropIfExists('departments');
     }
}
