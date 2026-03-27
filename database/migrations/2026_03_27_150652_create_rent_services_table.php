<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BusinessAccount;
use App\Models\RentServiceSubtype;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rent_services', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(BusinessAccount::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(RentServiceSubtype::class)
                ->constrained()
                ->restrictOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->decimal('price_usd', 12, 2);
            $table->decimal('price_syp', 15, 2);

            $table->tinyInteger('status')->default(1);
            $table->text('rejection_reason')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->unique(['business_account_id', 'rent_service_subtype_id'], 'rent_services_business_subtype_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_services');
    }
};
