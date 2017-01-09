<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShclinicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shclinic', function (Blueprint $table) {
            $table->increments('shc_id');
            $table->string('clinicname', 100)->default('');
            $table->string('region', 3)->default('');
            $table->string('province', 3)->default('');
            $table->string('municipality', 3)->default('');
            $table->string('hp_id', 100)->default('');
            $table->string('wallet_addr', 255)->default('');
            $table->string('image', 255)->default('')->nullable();
            $table->boolean('validated')->default(false);

            $table->string('api_token', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shclinic');
    }
}
