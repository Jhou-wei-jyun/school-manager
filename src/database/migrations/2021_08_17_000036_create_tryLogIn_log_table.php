<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTryloginLogTable extends Migration
{
    /**
     * Run the migrations.
     * @table tryLogIn_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tryLogIn_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('phone', 255);
            $table->string('brand', 255);
            $table->string('osVersion', 255);
            $table->string('deviceModel', 255);
            $table->dateTime('datetime');
            $table->string('ipaddress', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('tryLogIn_log');
     }
}
