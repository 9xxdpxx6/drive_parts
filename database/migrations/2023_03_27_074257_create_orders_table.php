<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('car_id')->nullable();
            $table->boolean('is_paid');
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->unsignedSmallInteger('status_id');
            $table->unsignedBigInteger('payment_method')->nullable();
            $table->text('comment')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('delivery')->nullable()->default(1);
            $table->unsignedBigInteger('delivery_price')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('manager_id');
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
        Schema::dropIfExists('orders');
    }
}
