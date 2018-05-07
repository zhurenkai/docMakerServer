<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markdowns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('api_id')->comment('项目id');
            $table->string('creator_name')->comment('项目id');
            $table->integer('creator_id')->comment('项目id');
            $table->string('last_updater_name')->comment('项目id');
            $table->integer('last_updater_id')->comment('项目id');
            $table->longText('content')->comment('内容');
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
        Schema::dropIfExists('markdown');
    }
}
