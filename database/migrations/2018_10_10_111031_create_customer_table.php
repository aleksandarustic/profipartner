<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',100);
            $table->string('provider',100);
            $table->string('provider_id',100);
            $table->string('email',100);
            $table->integer('points')->default(0);
            $table->string('api_token',100)->unique();
            $table->timestamps();
            $table->unique(['provider_id', 'provider']);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
