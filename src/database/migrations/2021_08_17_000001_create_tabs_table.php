<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabsTable extends Migration
{
    /**
     * Run the migrations.
     * @table tabs
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('tab_id');
            $table->integer('page_id')->nullable()->default(null);
            $table->string('tab_name', 255)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('tabs');
     }
}
