<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('name_en');
            $table->string('name_ar')->nullable();

            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['category_id', 'name_en']);
            $table->unique(['category_id', 'name_ar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
