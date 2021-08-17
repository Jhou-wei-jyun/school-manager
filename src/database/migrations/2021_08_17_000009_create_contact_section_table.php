<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactSectionTable extends Migration
{
    /**
     * Run the migrations.
     * @table contact_section
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_section', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('contact_id');
            $table->integer('section_id');
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
       Schema::dropIfExists('contact_section');
     }
}
