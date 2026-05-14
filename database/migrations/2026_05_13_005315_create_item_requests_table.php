<?php

use App\Models\Item;
use App\Models\User;
use App\Models\BusinessAccount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Item::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class, 'buyer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class, 'seller_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignIdFor(BusinessAccount::class, 'buyer_business_account_id')
                ->constrained('business_accounts')
                ->cascadeOnDelete();

            $table->foreignIdFor(BusinessAccount::class, 'seller_business_account_id')
                ->constrained('business_accounts')
                ->cascadeOnDelete();

            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'rejected',
            ])->default('pending');

            $table->text('message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_requests');
    }
};
