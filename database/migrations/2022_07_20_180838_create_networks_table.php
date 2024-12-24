<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'networks' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('networks', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // Network details
            $table->string('name', 96)->nullable();
            $table->text('description')->nullable();
            $table->string('host', 250)->nullable();
            $table->string('slug', 250)->unique()->nullable();

            // Localization settings
            $table->string('locale', 12)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('time_zone', 48)->nullable();

            // Network settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_undeletable')->default(false);
            $table->boolean('is_uneditable')->default(false);
            $table->boolean('is_primary')->default(false);

            // Meta information
            $table->json('meta')->nullable();

            // Ownership and timestamps
            $table->foreignId('created_by')->nullable()->constrained('admins')->cascadeOnDelete()->index('fk_networks_created_by');
            $table->foreignId('deleted_by')->nullable()->constrained('admins')->cascadeOnDelete()->index('fk_networks_deleted_by');
            $table->foreignId('updated_by')->nullable()->constrained('admins')->nullOnDelete()->index('fk_networks_updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'networks' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('networks', function (Blueprint $table) {
            $table->dropForeign('fk_networks_created_by');
            $table->dropForeign('fk_networks_deleted_by');
            $table->dropForeign('fk_networks_updated_by');
        });

        Schema::dropIfExists('networks');
    }
};
