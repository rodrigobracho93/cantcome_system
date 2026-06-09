<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('barcode')->constrained('users')->nullOnDelete();
            $table->string('price_status')->default('approved')->after('is_active');
            $table->foreignId('price_approved_by')->nullable()->after('price_status')->constrained('users')->nullOnDelete();
            $table->timestamp('price_approved_at')->nullable()->after('price_approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'price_status', 'price_approved_by', 'price_approved_at']);
        });
    }
};
