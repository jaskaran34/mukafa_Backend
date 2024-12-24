<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'transactions' table.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign keys and indexes for participants and relations
            // Save this information in case a staff member, card or reward is deleted
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete()->index('fk_transactions_staff_id');
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete()->index('fk_transactions_member_id');
            $table->foreignId('card_id')->nullable()->constrained('cards')->cascadeOnDelete()->index('fk_transactions_card_id');
            $table->foreignId('reward_id')->nullable()->constrained('rewards')->nullOnDelete()->index('fk_transactions_reward_id');

            // Participants' information
            $table->string('partner_name', 128)->nullable();
            $table->string('partner_email', 128);
            $table->string('staff_name', 128)->nullable();
            $table->string('staff_email', 128);
            $table->json('card_title')->nullable();
            $table->json('reward_title')->nullable();
            $table->unsignedInteger('reward_points')->nullable();

            // Transaction details
            $table->char('currency', 3)->nullable();
            $table->unsignedBigInteger('purchase_amount')->nullable();
            $table->integer('points');
            $table->unsignedInteger('points_used')->default(0);
            $table->unsignedInteger('currency_unit_amount')->nullable();
            $table->unsignedInteger('points_per_currency')->nullable();
            $table->decimal('point_value', 8, 4)->nullable();
            $table->unsignedInteger('min_points_per_purchase')->nullable();
            $table->unsignedInteger('max_points_per_purchase')->nullable();
            $table->unsignedInteger('min_points_per_redemption')->nullable();
            $table->unsignedInteger('max_points_per_redemption')->nullable();
            $table->string('event', 250)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Meta information
            $table->json('meta')->nullable();

            // Ownership and timestamps            
            $table->foreignId('created_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_transactions_created_by');
            $table->foreignId('deleted_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_transactions_deleted_by');
            $table->foreignId('updated_by')->nullable()->constrained('partners')->nullOnDelete()->index('fk_transactions_updated_by');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'transactions' table.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('fk_transactions_staff_id');
            $table->dropForeign('fk_transactions_member_id');
            $table->dropForeign('fk_transactions_card_id');
            $table->dropForeign('fk_transactions_reward_id');
            $table->dropForeign('fk_transactions_created_by');
            $table->dropForeign('fk_transactions_deleted_by');
            $table->dropForeign('fk_transactions_updated_by');
        });

        Schema::dropIfExists('transactions');
    }
};
