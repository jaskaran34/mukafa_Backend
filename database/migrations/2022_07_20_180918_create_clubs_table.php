<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the 'clubs' table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('id');

            // Club details
            $table->string('name', 96)->nullable();
            $table->text('description')->nullable();

            // Localization settings
            $table->string('locale', 12)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('time_zone', 48)->nullable();

            // Club settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_undeletable')->default(false);
            $table->boolean('is_uneditable')->default(false);

            // Meta information
            $table->json('meta')->nullable();

            // Ownership and timestamps
            $table->foreignId('created_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_clubs_created_by');
            $table->foreignId('deleted_by')->nullable()->constrained('partners')->cascadeOnDelete()->index('fk_clubs_deleted_by');
            $table->foreignId('updated_by')->nullable()->constrained('partners')->nullOnDelete()->index('fk_clubs_updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations to drop the 'clubs' table.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropForeign('fk_clubs_created_by');
            $table->dropForeign('fk_clubs_deleted_by');
            $table->dropForeign('fk_clubs_updated_by');
        });

        Schema::dropIfExists('clubs');
    }
};
