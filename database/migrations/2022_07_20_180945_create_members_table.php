<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'members' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // Foreign key
            $table->foreignId('affiliate_id')->nullable()->constrained('affiliates')->nullOnDelete()->index('fk_members_affiliate_id');

            // Member account details
            $table->tinyInteger('role')->default(1)->comment('1 = regular member');
            $table->string('member_number', 32)->nullable()->unique()->comment('Unique number in format of: xxx-xxx-xxx-xxx');
            $table->string('unique_identifier', 32)->nullable()->unique()->comment('Unique identifier');
            $table->string('display_name', 64)->nullable()->comment('Visible to other users');
            $table->string('name', 128)->nullable();
            $table->string('email', 128)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->date('birthday')->nullable();
            $table->tinyInteger('gender')->default(0)->comment('0 = unknown, 1 = male, 2 = female');

            // Two-Factor Authentication
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();

            // Account expiration and premium status
            $table->timestamp('account_expires_at')->nullable();
            $table->timestamp('premium_expires_at')->nullable();

            // Localization settings
            $table->string('locale', 12)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('time_zone', 48)->nullable();

            // Phone details
            $table->string('phone_prefix', 4)->nullable();
            $table->string('phone_country', 2)->nullable();
            $table->string('phone', 24)->nullable();
            $table->string('phone_e164', 24)->nullable();

            // Member account settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_vip')->default(false);
            $table->boolean('accepts_emails')->default(false);
            $table->boolean('accepts_text_messages')->default(false);
            $table->boolean('is_undeletable')->default(false);
            $table->boolean('is_uneditable')->default(false);

            // Interaction stats
            $table->unsignedInteger('number_of_times_logged_in')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->unsignedInteger('number_of_emails_received')->default(0);
            $table->unsignedInteger('number_of_text_messages_received')->default(0);
            $table->unsignedInteger('number_of_reviews_written')->default(0);
            $table->unsignedInteger('number_of_ratings_given')->default(0);

            // Meta information
            $table->json('meta')->nullable();

            // Ownership and timestamps
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete()->index('fk_members_created_by');
            $table->foreignId('deleted_by')->nullable()->constrained('admins')->nullOnDelete()->index('fk_members_deleted_by');
            $table->foreignId('updated_by')->nullable()->constrained('admins')->nullOnDelete()->index('fk_members_updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'members' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign('fk_members_affiliate_id');
            $table->dropForeign('fk_members_created_by');
            $table->dropForeign('fk_members_deleted_by');
            $table->dropForeign('fk_members_updated_by');
        });

        Schema::dropIfExists('members');
    }
};
