<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     * @table notifies
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->text('message');
            $table->bigInteger('statu_id')->nullable()->default(null);
            $table->longText('image')->nullable()->default(null);
            $table->integer('sent_id')->nullable()->default(null);
            $table->integer('school_id')->nullable()->default(null);
            $table->string('target', 100)->nullable()->default(null);
            $table->json('relationship')->nullable()->default(null);
            $table->string('sent_type', 100)->nullable()->default(null);
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
       Schema::dropIfExists('notifies');
     }
}
