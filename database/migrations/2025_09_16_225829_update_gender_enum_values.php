<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update employees table gender enum
        DB::statement("ALTER TABLE employees MODIFY COLUMN gender ENUM('male', 'female')");
        
        // Update clients table gender enum
        DB::statement("ALTER TABLE clients MODIFY COLUMN gender ENUM('male', 'female')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE employees MODIFY COLUMN gender ENUM('male', 'female', 'other')");
        DB::statement("ALTER TABLE clients MODIFY COLUMN gender ENUM('male', 'female', 'other')");
    }
};
