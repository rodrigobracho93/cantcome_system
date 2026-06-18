<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almuerzos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->boolean('entregado')->default(false);
            $table->timestamp('entregado_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->unique(['customer_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almuerzos');
    }
};
