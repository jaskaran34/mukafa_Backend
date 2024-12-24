<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'cards' table.
     */
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign key
            $table->foreignId('club_id')->nullable()->constrained('clubs')->cascadeOnDelete()->index();

            // Card content and details
            $table->string('name', 250);
            $table->string('type', 32)->default('loyalty');
            $table->string('icon', 32)->nullable();
            $table->json('head')->nullable();
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->string('unique_identifier', 32)->nullable()->unique(); // Unique number in format of: xxx-xxx-xxx-xxx
            $table->timestamp('issue_date')->useCurrent();
            $table->timestamp('expiration_date')->nullable();

            // Card design
            $table->string('bg_color', 25)->nullable();
            $table->tinyInteger('bg_color_opacity')->nullable();
            $table->string('text_color', 32)->nullable();
            $table->string('text_label_color', 32)->nullable();
            $table->string('qr_color_light', 32)->nullable();
            $table->string('qr_color_dark', 32)->nullable();

            // Card features and settings
            $table->char('currency', 3)->nullable();
            $table->unsignedInteger('initial_bonus_points')->nullable();
            $table->unsignedInteger('points_expiration_months')->nullable();
            $table->unsignedInteger('currency_unit_amount')->nullable();
            $table->unsignedInteger('points_per_currency')->nullable();
            $table->decimal('point_value', 8, 4)->unsigned()->nullable();
            $table->unsignedBigInteger('min_points_per_purchase')->nullable();
            $table->unsignedBigInteger('max_points_per_purchase')->nullable();
            $table->unsignedBigInteger('min_points_per_redemption')->nullable();
            $table->unsignedBigInteger('max_points_per_redemption')->nullable();
            $table->json('custom_rule1')->nullable();
            $table->json('custom_rule2')->nullable();
            $table->json('custom_rule3')->nullable();

            // Card activation and visibility
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible_by_default')->default(false);
            $table->boolean('is_visible_when_logged_in')->default(false);
            $table->boolean('is_undeletable')->default(false);
            $table->boolean('is_uneditable')->default(false);

            // Card statistics
            $table->unsignedInteger('total_amount_purchased')->default(0);
            $table->unsignedInteger('number_of_points_issued')->default(0);
            $table->timestamp('last_points_issued_at')->nullable();
            $table->unsignedInteger('number_of_points_redeemed')->default(0);
            $table->unsignedInteger('number_of_rewards_redeemed')->default(0);
            $table->timestamp('last_reward_redeemed_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('last_view')->nullable();

            // Meta information
            $table->json('meta')->nullable();

            // Ownership and timestamps            $table->foreignId('club_id')->nullable()->constrained('clubs')->cascadeOnDelete()->index('fk_cards_club_id');
            $table->foreignId('created_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_cards_created_by');
            $table->foreignId('deleted_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_cards_deleted_by');
            $table->foreignId('updated_by')->nullable()->constrained('partners')->nullOnDelete()->index('fk_cards_updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'cards' table.
     */
    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropForeign('fk_cards_club_id');
            $table->dropForeign('fk_cards_created_by');
            $table->dropForeign('fk_cards_deleted_by');
            $table->dropForeign('fk_cards_updated_by');
        });

        Schema::dropIfExists('cards');
    }
};
