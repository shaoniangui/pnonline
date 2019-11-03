<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->rememberToken();
            $table->timestamps();

            //微信资料字段
            $table->string('weapp_openid')->nullable()->comment('微信开放id');
            $table->string('weapp_session_key')->nullable()->comment('微信session_key');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('weapp_avatar')->nullable()->comment('微信头像');
            $table->string('country')->nullable()->comment('国家');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('所在城市');
            $table->string('language')->nullable()->comment('语言');
            $table->json('location')->nullable()->comment('当前地理信息');
            $table->enum('gender', ['1', '2'])->default('1')->comment('性别默认男');
            $table->string('phone')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
