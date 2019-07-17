<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('添加者');
            $table->string('name')->comment('产品名称');
            $table->tinyInteger('type',0)->comment('产品类型 0 PC网站 1H5网站 2安卓APP 3IOS APP 4微信小程序 5可执行程序 6其他');
            $table->string('url')->comment('产品主要地址 可以填官网类地址');
            $table->tinyInteger('status',1)->comment('产品状态  0作废 1正常 2禁止访问');
            $table->tinyInteger('is_self',1)->comment('是否是 自由的产品 还是外包的产品 ');
            $table->string('remark')->nullable()->comment('产品简要说明');

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
        Schema::dropIfExists('products');
    }
}
