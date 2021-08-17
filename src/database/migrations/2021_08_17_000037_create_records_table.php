<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     * @table records
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->default(null);
            $table->bigInteger('item_id')->nullable()->default(null);
            $table->bigInteger('area_id');
            $table->integer('rssi');
            $table->string('battery', 255);
            $table->string('date_time', 40)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('leave_at', 255)->nullable()->default(null);
            $table->string('start_timestamp', 255)->nullable()->default(null);
            $table->string('leave_timestamp', 255)->nullable()->default(null);
            $table->bigInteger('statu_id')->nullable()->default(null);
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
       Schema::dropIfExists('records');
     }
}
