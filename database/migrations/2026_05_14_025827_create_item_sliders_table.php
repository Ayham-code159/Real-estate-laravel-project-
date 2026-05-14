<?php

use App\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_sliders', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Item::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('is_active')->default(true);

            $table->string('priority')->default('normal'); // normal / high / top

            $table->unsignedBigInteger('click_count')->default(0);

            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->unique('item_id');
            $table->index('is_active');
            $table->index('priority');
            $table->index('click_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_sliders');
    }
};
