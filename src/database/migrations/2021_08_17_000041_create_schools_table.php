<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     * @table schools
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('school_id');
            $table->string('school_name', 200)->nullable()->default(null);
            $table->string('school_ename', 500)->nullable()->default(null);
            $table->string('school_class', 100)->nullable()->default(null);
            $table->string('school_info', 500)->nullable()->default(null);
            $table->string('school_log', 500)->nullable()->default(null);
            $table->string('school_url', 100)->nullable()->default(null);
            $table->integer('teacher_type')->nullable()->default(null);
            $table->integer('student_type')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('schools');
     }
}
