<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     * @table logIn_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logIn_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('phone', 255);
            $table->string('brand', 255);
            $table->string('osVersion', 255);
            $table->string('deviceModel', 255);
            $table->string('uuid', 255);
            $table->dateTime('login_datetime')->nullable()->default(null);
            $table->dateTime('signout_datetime')->nullable()->default(null);
            $table->string('ipaddress', 100);
            $table->string('app_version', 100)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('logIn_log');
     }
}
