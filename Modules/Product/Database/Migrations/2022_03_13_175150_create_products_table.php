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
            $table->string('name');
            $table->string('code')->unique();
            $table->string('barcode_symbology');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->unsignedBigInteger('purchase_unit_id');
            $table->foreign('purchase_unit_id')->references('id')->on('units');
            $table->unsignedBigInteger('sale_unit_id');
            $table->foreign('sale_unit_id')->references('id')->on('units');
            $table->double('cost');
            $table->double('price')->nullable();
            $table->double('qty')->nullable();
            $table->double('alert_qty')->nullable();
            $table->unsignedBigInteger('tax_id');
            $table->foreign('tax_id')->references('id')->on('taxes')->nullable();
            $table->enum('tax_method', ['1', '2'])->comment='1=Exclusive,2=Inclusive';
            $table->text('description')->nullable();
            $table->enum('status', ['1', '2'])->default('1')->comment('1=Active,2=Inactive');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
