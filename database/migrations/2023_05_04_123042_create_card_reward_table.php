<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'card_reward' pivot table.
     */
    public function up(): void
    {
        Schema::create('card_reward', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->cascadeOnDelete()->index('fk_card_reward_card_id');
            $table->foreignId('reward_id')->constrained('rewards')->cascadeOnDelete()->index('fk_card_reward_reward_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'card_reward' pivot table.
     */
    public function down(): void
    {
        Schema::table('card_reward', function (Blueprint $table) {
            $table->dropForeign('fk_card_reward_card_id');
            $table->dropForeign('fk_card_reward_reward_id');
        });

        Schema::dropIfExists('card_reward');
    }
};
