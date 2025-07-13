<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table): void {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('publisher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('synopsis');
            $table->string('slug')->unique();
            $table->string('isbn', 13)->unique();
            $table->string('language', 2)->default('en');
            $table->unsignedInteger('page_count');
            $table->string('format');
            $table->string('cover_url')->nullable();
            $table->date('released_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
