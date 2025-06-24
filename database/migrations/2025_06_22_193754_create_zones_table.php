<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // commercial
            $table->timestamps();
        });

        Schema::table('pharmacies', function (Blueprint $table) {
            $table->foreignId('zone_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('zone_id');
        });

        Schema::dropIfExists('zones');
    }
};
