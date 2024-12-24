<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'staff' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // Foreign key            
            $table->foreignId('club_id')->nullable()->constrained('clubs')->nullOnDelete()->index('fk_staff_club_id');

            // Staff account details
            $table->tinyInteger('role')->default(1)->comment('1 = regular staff member');
            $table->string('unique_identifier', 32)->nullable()->unique()->comment('Unique identifier');
            $table->string('display_name', 64)->nullable()->comment('Visible to other users');
            $table->string('name', 128)->nullable();
            $table->string('email', 128)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();

            // Two-Factor Authentication
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();

            // Account expiration
            $table->timestamp('account_expires_at')->nullable();

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

            // Staff account settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_undeletable')->default(false);
            $table->boolean('is_uneditable')->default(false);

            // Login stats
            $table->unsignedInteger('number_of_times_logged_in')->default(0);
            $table->timestamp('last_login_at')->nullable();

            // Meta information
            $table->json('meta')->nullable();

            // Ownership and timestamps
            $table->foreignId('created_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_staff_created_by');
            $table->foreignId('deleted_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_staff_deleted_by');
            $table->foreignId('updated_by')->nullable()->constrained('partners')->nullOnDelete()->index('fk_staff_updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'staff' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign('fk_staff_club_id');
            $table->dropForeign('fk_staff_created_by');
            $table->dropForeign('fk_staff_deleted_by');
            $table->dropForeign('fk_staff_updated_by');
        });

        Schema::dropIfExists('staff');
    }
};
