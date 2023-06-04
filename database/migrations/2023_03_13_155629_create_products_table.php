<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('part_number');
            $table->string('name');
            $table->string('alias')->unique();
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('price', 10, 2)->default(0,00);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->integer('qty')->default(0);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->boolean('is_active')->default(true);
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
