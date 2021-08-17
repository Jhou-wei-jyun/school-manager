<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     * @table contacts
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('condition', 255)->nullable()->default(null);
            $table->string('temperature', 255)->nullable()->default(null);
            $table->string('return', 255)->nullable()->default(null);
            $table->string('bring', 255)->nullable()->default(null);
            $table->text('to_parent')->nullable()->default(null);
            $table->text('to_teacher')->nullable()->default(null);
            $table->integer('user_id');
            $table->integer('teacher_id')->nullable()->default(null);
            $table->integer('parent_id')->nullable()->default(null);
            $table->integer('school_id');
            $table->integer('status')->nullable()->default('0');
            $table->text('daily')->nullable()->default(null);
            $table->date('onboard_date')->nullable()->default(null);
            $table->integer('mood')->nullable()->default(null);
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
       Schema::dropIfExists('contacts');
     }
}
