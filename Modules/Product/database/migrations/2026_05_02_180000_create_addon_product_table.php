<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Addon;
use Modules\Product\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Addon::class)->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['product_id', 'addon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_product');
    }
};
