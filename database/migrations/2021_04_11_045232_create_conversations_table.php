<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('conversation_table', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contact');
            $table->unsignedBigInteger('id_path');
            $table->timestamps();
            //foreign key
            $table->foreign('id_contact')->references('id')->on('contact_table')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_path')->references('id')->on('path_table')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
