<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'default_employee_id', // make sure this is here
    ];

    // Relationship to all assigned employees (pivot table)
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    // Relationship to the default employee
    public function default_employee()
    {
        return $this->belongsTo(Employee::class, 'default_employee_id');
    }
}
