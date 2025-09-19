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
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing primary key column named 'id'.
            $table->string('full_name'); // Creates a VARCHAR column for the client's name.
            $table->string('phone')->unique(); // A VARCHAR column for the phone, marked as unique.
            $table->string('address')->nullable(); // A VARCHAR for the address, which can be empty (null).
            $table->text('notes')->nullable(); // A TEXT column for longer notes, also optional.
            $table->timestamps(); // Creates 'created_at' and 'updated_at' columns automatically.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};