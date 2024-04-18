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
        Schema::create('contract_models_gabarit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_model_id')->constrained('contract_models')->onDelete('cascade');
            $table->foreignId('gabarit_id')->constrained('gabarits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_models_gabarit');
    }
};
