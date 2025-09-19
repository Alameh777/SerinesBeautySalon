<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // This sets up the relationship with the 'clients' table.
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            // Relationship with the 'services' table.
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            // Relationship with the 'employees' table.
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            
            $table->dateTime('start_time'); 
            $table->dateTime('end_time');
            $table->string('payment_status')->default('unpaid'); 
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->json('services_employees')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};