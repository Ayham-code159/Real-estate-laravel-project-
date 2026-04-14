<?php

use App\Models\User;
use App\Models\BusinessAccount;
use App\Models\ServiceListing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(ServiceListing::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('buyer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('buyer_business_account_id')
                ->constrained('business_accounts')
                ->cascadeOnDelete();

            $table->foreignId('seller_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('seller_business_account_id')
                ->constrained('business_accounts')
                ->cascadeOnDelete();

            $table->dateTime('requested_for');

            $table->text('description');
            $table->json('request_metadata')->nullable();

            $table->decimal('price_usd_snapshot', 12, 2);
            $table->decimal('price_syp_snapshot', 15, 2);

            $table->tinyInteger('status')->default(1);

            $table->text('seller_response_note')->nullable();

            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_requests');
    }
};
