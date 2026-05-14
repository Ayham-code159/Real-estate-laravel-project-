<?php

use App\Models\Item;
use App\Models\User;
use App\Models\ItemRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(ItemRequest::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Item::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class, 'buyer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class, 'seller_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');

            $table->text('review')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_ratings');
    }
};
