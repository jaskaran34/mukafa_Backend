<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'admin_network' pivot table.
     */
    public function up(): void
    {
        Schema::create('admin_network', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete()->index('fk_admin_network_admin_id');
            $table->foreignId('network_id')->constrained('networks')->cascadeOnDelete()->index('fk_admin_network_network_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_network', function (Blueprint $table) {
            $table->dropForeign('fk_admin_network_admin_id');
            $table->dropForeign('fk_admin_network_network_id');
        });

        Schema::dropIfExists('admin_network');
    }
};
