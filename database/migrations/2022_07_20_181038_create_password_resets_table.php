<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'password_resets' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            // Email of the user requesting the password reset
            $table->string('email')->index();

            // Token for resetting the password
            $table->string('token');

            // Timestamp when the password reset request was created
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations to drop the 'password_resets' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
};
