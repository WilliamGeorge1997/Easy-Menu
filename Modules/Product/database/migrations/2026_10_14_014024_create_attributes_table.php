<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Branch\Models\Branch;
use Modules\Product\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Branch::class)->index()->constrained()->cascadeOnDelete();
            $table->json('title');
            $table->boolean('required')->default(false);
            $table->boolean('multi_select')->default(false);
            $table->boolean('override_price')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(1);
            $table->enum('type', ['size'])->default('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
