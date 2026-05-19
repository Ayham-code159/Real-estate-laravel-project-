<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->default('basic')->after('status');
            $table->unsignedTinyInteger('business_account_limit')->default(0)->after('plan');
            $table->string('stripe_customer_id')->nullable()->after('business_account_limit');
            $table->timestamp('plan_paid_at')->nullable()->after('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'plan',
                'business_account_limit',
                'stripe_customer_id',
                'plan_paid_at',
            ]);
        });
    }
};
