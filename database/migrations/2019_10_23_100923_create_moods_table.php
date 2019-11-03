<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100)->comment('话题标题');
            $table->mediumText('content')->comment('话题内容');
            $table->integer('share_num')->default(0)->comment('分享数量');
            $table->integer('read_num')->default(0)->comment('阅读量');
            $table->unsignedInteger('author_id');
            $table->boolean('mosaic')->comment('是否打码');
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
        Schema::dropIfExists('moods');
    }
}
