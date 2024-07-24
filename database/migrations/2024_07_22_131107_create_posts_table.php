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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Pet::class, 'pet_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->boolean('hide_like_view')->default(false);
            $table->boolean('allow_commenting')->default(false);
            $table->enum('type', ['post', 'reel']);
            $table->enum('visibility', ['public', 'private']);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
