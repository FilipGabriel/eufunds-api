<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductsTableAddNewStockColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('supplier_stock')->nullable()->after('qty');
            $table->date('supplier_stock_date')->nullable()->after('supplier_stock');
            $table->integer('reserved_stock')->nullable()->after('supplier_stock_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('supplier_stock');
            $table->dropColumn('special_price_valid_to');
            $table->dropColumn('reserved_stock');
        });
    }
}
