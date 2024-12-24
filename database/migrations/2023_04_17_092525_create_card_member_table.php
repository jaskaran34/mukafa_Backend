<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'card_member' pivot table.
     */
    public function up(): void
    {
        Schema::create('card_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->cascadeOnDelete()->index('fk_card_member_card_id');
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete()->index('fk_card_member_member_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_member', function (Blueprint $table) {
            $table->dropForeign('fk_card_member_card_id');
            $table->dropForeign('fk_card_member_member_id');
        });

        Schema::dropIfExists('card_member');
    }
};
