<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->index('sale_id');
        });

        Schema::table('caja_movimientos', function (Blueprint $table) {
            $table->index('caja_id');
        });

        Schema::table('almuerzos', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('fecha');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('created_at');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
        });

        Schema::table('caja_movimientos', function (Blueprint $table) {
            $table->dropIndex(['caja_id']);
        });

        Schema::table('almuerzos', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['fecha']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
        });
    }
};
