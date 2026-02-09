<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inmates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->ulid('public_id')->unique();
            $table->string('full_name');
            $table->string('inmate_number', 100)->unique();
            $table->string('gender', 20);
            $table->date('birth_date')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inmates');
    }
};
