<?php

use App\Models\Service;
use App\Models\BusinessAccount;
use App\Models\ServiceSubcategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_listings', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(BusinessAccount::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Service::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(ServiceSubcategory::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('mode');
            $table->decimal('price_usd', 12, 2);
            $table->decimal('price_syp', 15, 2);

            $table->json('metadata')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_listings');
    }
};
