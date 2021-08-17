<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     * @table materials
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name', 255)->nullable()->default(null);
            $table->string('url', 255);
            $table->bigInteger('department_id');
            $table->tinyInteger('active')->default('1');
            $table->nullableTimestamps();


            $table->foreign('department_id', 'materials_department_id_foreign')
                ->references('id')->on('areas')
                ->onDelete('')
                ->onUpdate('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('materials');
     }
}
