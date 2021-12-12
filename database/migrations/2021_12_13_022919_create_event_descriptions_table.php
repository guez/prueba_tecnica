<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_descriptions', function (Blueprint $table) {
            $table->id();

            $table->string("lenguage")->default("es");
            $table->string("name");
            
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            
            $table->unique(['event_id',"lenguage"]);

            $table->softDeletes();
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
        Schema::dropIfExists('event_descriptions');
    }
}
