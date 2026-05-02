<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Addon;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Addon::class)->index()->constrained()->cascadeOnDelete();
            $table->json('title');
            $table->decimal('price', 10, 2)->unsigned()->default(0.00);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_values');
    }
};
