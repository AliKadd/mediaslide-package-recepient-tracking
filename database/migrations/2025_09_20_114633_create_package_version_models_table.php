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
        Schema::create('package_version_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_version_id');
            $table->unsignedBigInteger('model_id');
            $table->json('model_snapshot')->nullable();
            $table->boolean('shortlisted')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('package_version_id')->references('id')->on('package_versions')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_version_models');
    }
};
