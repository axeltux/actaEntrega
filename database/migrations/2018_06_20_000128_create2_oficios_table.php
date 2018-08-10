<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create2OficiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oficios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('oficio');
            $table->integer('cerys');
            $table->string('lotes');
            $table->dateTime('creadoEl')->nullable();
            $table->boolean('firmado')->default(0);
            $table->string('firmadoPor')->nullable();
            $table->string('RFC')->nullable();
            $table->string('firmaDigital', 4000)->nullable();
            $table->string('selloDigital', 4000)->nullable();            
            $table->dateTime('firmadoEl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oficios');
    }
}
