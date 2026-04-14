<?php

use App\Models\User;
use App\Models\ServiceListing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_request_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('listing_request_id')
                ->constrained('listing_requests')
                ->cascadeOnDelete();

            $table->foreignIdFor(ServiceListing::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('buyer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique('listing_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_request_ratings');
    }
};
