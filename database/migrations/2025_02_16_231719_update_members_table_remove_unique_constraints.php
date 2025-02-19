<?php

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
        Schema::table('members', function (Blueprint $table) {
            // Drop the unique constraint on the 'email' column
            $table->dropUnique('members_email_unique');

            // Drop the unique constraint on the 'phone' column
            $table->dropUnique('members_phone_unique');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Restore the unique constraint on the 'email' column
            $table->unique('email');

            // Restore the unique constraint on the 'phone' column
            $table->unique('phone');
        });
    }
};
