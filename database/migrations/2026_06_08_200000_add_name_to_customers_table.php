<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable();
        });

        Customer::whereNull('name')->each(fn($c) => $c->update(['name' => trim("{$c->first_name} {$c->last_name}")]));
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
