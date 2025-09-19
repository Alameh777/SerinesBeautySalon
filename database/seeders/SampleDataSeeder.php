<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample clients
        $clients = [
            ['full_name' => 'Sarah Johnson', 'phone' => '555-0101', 'address' => '123 Main St', 'notes' => 'Prefers morning appointments'],
            ['full_name' => 'Emily Davis', 'phone' => '555-0102', 'address' => '456 Oak Ave', 'notes' => 'Regular customer'],
            ['full_name' => 'Michael Brown', 'phone' => '555-0103', 'address' => '789 Pine Rd', 'notes' => 'New client'],
            ['full_name' => 'Lisa Wilson', 'phone' => '555-0104', 'address' => '321 Elm St', 'notes' => 'VIP customer'],
            ['full_name' => 'David Miller', 'phone' => '555-0105', 'address' => '654 Maple Dr', 'notes' => 'Prefers evening appointments'],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        // Create sample employees
        $employees = [
            ['name' => 'Jessica Martinez', 'email' => 'jessica@salon.com', 'phone' => '555-0201'],
            ['name' => 'Amanda Taylor', 'email' => 'amanda@salon.com', 'phone' => '555-0202'],
            ['name' => 'Rachel Anderson', 'email' => 'rachel@salon.com', 'phone' => '555-0203'],
            ['name' => 'Nicole Garcia', 'email' => 'nicole@salon.com', 'phone' => '555-0204'],
        ];

        foreach ($employees as $employeeData) {
            Employee::create($employeeData);
        }

        // Create sample services
        $services = [
            ['name' => 'Haircut', 'description' => 'Professional haircut and styling', 'price' => 45.00, 'duration' => 60],
            ['name' => 'Hair Color', 'description' => 'Full hair coloring service', 'price' => 120.00, 'duration' => 120],
            ['name' => 'Manicure', 'description' => 'Professional nail care and polish', 'price' => 35.00, 'duration' => 45],
            ['name' => 'Pedicure', 'description' => 'Foot care and nail polish', 'price' => 50.00, 'duration' => 60],
            ['name' => 'Facial', 'description' => 'Deep cleansing facial treatment', 'price' => 80.00, 'duration' => 90],
            ['name' => 'Massage', 'description' => 'Relaxing full body massage', 'price' => 100.00, 'duration' => 60],
            ['name' => 'Eyebrow Shaping', 'description' => 'Professional eyebrow shaping', 'price' => 25.00, 'duration' => 30],
            ['name' => 'Makeup Application', 'description' => 'Professional makeup for special occasions', 'price' => 75.00, 'duration' => 90],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        // Create employee-service relationships
        $employees = Employee::all();
        $services = Service::all();

        // Jessica can do hair services
        $jessica = $employees->where('name', 'Jessica Martinez')->first();
        $jessica->services()->attach([1, 2]); // Haircut, Hair Color

        // Amanda can do nail services
        $amanda = $employees->where('name', 'Amanda Taylor')->first();
        $amanda->services()->attach([3, 4]); // Manicure, Pedicure

        // Rachel can do facial and massage
        $rachel = $employees->where('name', 'Rachel Anderson')->first();
        $rachel->services()->attach([5, 6]); // Facial, Massage

        // Nicole can do makeup and eyebrow services
        $nicole = $employees->where('name', 'Nicole Garcia')->first();
        $nicole->services()->attach([7, 8]); // Eyebrow Shaping, Makeup Application

        // Some employees can do multiple services
        $jessica->services()->attach([7]); // Jessica can also do eyebrow shaping
        $amanda->services()->attach([7]); // Amanda can also do eyebrow shaping
    }
}