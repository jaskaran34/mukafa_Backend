<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'rewards' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // Reward details
            $table->string('name', 250);
            $table->json('title');
            $table->json('description')->nullable();
            $table->integer('max_number_to_redeem')->default(0);
            $table->unsignedInteger('points');
            $table->timestamp('active_from')->nullable();
            $table->timestamp('expiration_date')->nullable();

            // Reward activation
            $table->boolean('is_active')->default(true);

            // Reward statistics
            $table->unsignedInteger('number_of_times_redeemed')->default(0);
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('last_view')->nullable();
            $table->boolean('is_undeletable')->default(false);
            $table->boolean('is_uneditable')->default(false);

            // Meta information
            $table->json('meta')->nullable();

            // Ownership and timestamps
            $table->foreignId('created_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_rewards_created_by');
            $table->foreignId('deleted_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_rewards_deleted_by');
            $table->foreignId('updated_by')->nullable()->constrained('partners')->nullOnDelete()->index('fk_rewards_updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'rewards' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropForeign('fk_rewards_created_by');
            $table->dropForeign('fk_rewards_deleted_by');
            $table->dropForeign('fk_rewards_updated_by');
        });

        Schema::dropIfExists('rewards');
    }
};
