<?php

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\BusinessAccount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(BusinessAccount::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Category::class)
                ->constrained()
                ->restrictOnDelete();

            $table->foreignIdFor(Subcategory::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('title');
            $table->string('title_en');
            $table->string('title_ar')->nullable();

            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();

            $table->string('item_type'); // sell / rent

            $table->decimal('price_usd', 15, 2);
            $table->decimal('price_syp', 15, 2);

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('location_label')->nullable();

            $table->json('dynamic_values')->nullable();

            $table->unsignedTinyInteger('status')->default(1);
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->index('business_account_id');
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('status');
            $table->index('item_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
