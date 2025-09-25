<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
    'name',
    'email',
    'phone',
    'gender',

    ];

    public function services()
{
    return $this->belongsToMany(Service::class, 'employee_service');
}

}
