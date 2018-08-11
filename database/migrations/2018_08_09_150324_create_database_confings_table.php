<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseConfingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->comment('项目id');
            $table->string('creator_name')->comment('创建者姓名');
            $table->string('host')->comment('创建者姓名');
            $table->integer('port')->comment('创建者姓名');
            $table->string('username')->comment('用户名');
            $table->string('password')->nullable()->comment('密码');
            $table->string('databases')->comment('数据库');
            $table->integer('creator_id')->comment('创建者id');
            $table->string('last_updater_name')->comment('最后更新者姓名');
            $table->integer('last_updater_id')->comment('最后更新者姓名id');
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
        //
    }
}
