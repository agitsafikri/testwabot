<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('path_table', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nodeBefore');
            $table->unsignedBigInteger('id_nodeAfter');
            $table->unsignedBigInteger('id_rule');
            $table->string('key');
            //foreign key
            $table->foreign('id_nodeBefore')->references('id')->on('node_table')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_nodeAfter')->references('id')->on('node_table')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_rule')->references('id')->on('rule_table')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paths');
    }
}
