<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQrcodesTable extends Migration
{
    /**
     * Run the migrations.
     * @table qrcodes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('qrcode_id');
            $table->bigInteger('parent_id');
            $table->string('token', 60);
            $table->timestamp('start_time')->nullable()->default(null);
            $table->json('parmas')->nullable()->default(null);

            $table->unique(["parent_id"], 'unique_qrcodes');
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
       Schema::dropIfExists('qrcodes');
     }
}
