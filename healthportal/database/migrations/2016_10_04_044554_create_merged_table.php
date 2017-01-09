<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMergedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merged', function (Blueprint $table) {
            $table->string('central_id', 250);
            $table->string('client_id', 250);

            $table->boolean('invalid');
            $table->string('guid', 25);
            $table->timestamp('created_on');
            $table->integer('counter_last_update');

            $table->timestamps();

            $table->primary(['central_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merged');
    }
}
