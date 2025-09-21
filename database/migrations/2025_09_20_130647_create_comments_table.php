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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_recipient_id')->nullable();
            $table->unsignedBigInteger('package_version_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();

            $table->index('model_id');
            $table->index('recipient_id');
            $table->index('package_version_id');
            $table->index('package_recipient_id');

            $table->foreign('package_recipient_id')->references('id')->on('package_recipients')->onDelete('cascade');
            $table->foreign('package_version_id')->references('id')->on('package_versions')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('models')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('recipients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
