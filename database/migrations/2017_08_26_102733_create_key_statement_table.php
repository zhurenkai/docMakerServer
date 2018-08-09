<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->comment('字段名');
            $table->string('statement')->comment('描述');
            $table->string('type')->comment('类型');
            $table->string('key_type_state_hash')->comment('key type statement 拼接后的md5')->index();
            $table->string('user_id')->comment('用户id');
            $table->string('project_id')->comment('项目id');
            $table->integer('weight')->default(0)->comment('比重');
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
        Schema::dropIfExists('key_statements');
    }
}
