<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'analytics' table.
     */
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete()->index('fk_analytics_partner_id');
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete()->index('fk_analytics_member_id');
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete()->index('fk_analytics_staff_id');
            $table->foreignId('card_id')->nullable()->constrained('cards')->cascadeOnDelete()->index('fk_analytics_card_id');
            $table->foreignId('reward_id')->nullable()->constrained('rewards')->nullOnDelete()->index('fk_analytics_reward_id');
            $table->string('event', 250)->nullable();
            $table->string('locale', 12)->nullable();
            $table->char('currency', 3)->nullable();
            $table->unsignedBigInteger('purchase_amount')->nullable();
            $table->integer('points')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'analytics' table.
     */
    public function down(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropForeign('fk_analytics_partner_id');
            $table->dropForeign('fk_analytics_member_id');
            $table->dropForeign('fk_analytics_staff_id');
            $table->dropForeign('fk_analytics_card_id');
            $table->dropForeign('fk_analytics_reward_id');
        });

        Schema::dropIfExists('analytics');
    }
};
