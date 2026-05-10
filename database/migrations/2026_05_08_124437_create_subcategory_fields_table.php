<?php

use App\Models\Subcategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subcategory_fields', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Subcategory::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('field_key');

            $table->string('label_en');
            $table->string('label_ar')->nullable();

            $table->string('field_type'); // text / number / select / boolean / date

            $table->boolean('is_required')->default(false);

            $table->json('options')->nullable(); // for select fields

            $table->decimal('min_value', 15, 2)->nullable();
            $table->decimal('max_value', 15, 2)->nullable();

            $table->string('text_rule')->nullable(); // none / letters_only / letters_spaces_only / alpha_numeric

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['subcategory_id', 'field_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategory_fields');
    }
};
