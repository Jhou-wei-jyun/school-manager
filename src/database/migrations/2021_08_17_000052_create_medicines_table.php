<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicines
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('reason', 255)->nullable()->default(null);
            $table->text('notation')->nullable()->default(null);
            $table->string('time', 255)->nullable()->default(null);
            $table->integer('pack')->nullable()->default(null);
            $table->string('cc', 255)->nullable()->default(null);
            $table->string('other', 255)->nullable()->default(null);
            $table->integer('user_id');
            $table->integer('school_id');
            $table->tinyInteger('checked')->nullable()->default('0');
            $table->integer('status')->nullable()->default('0');
            $table->string('taken', 255)->nullable()->default(null);
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
       Schema::dropIfExists('medicines');
     }
}
