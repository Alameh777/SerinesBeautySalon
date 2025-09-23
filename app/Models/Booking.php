<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
protected $fillable = [
    'client_id',
    'start_time',
    'notes',
    'payment_status',
        
];

    protected $casts = [
    'services_employees' => 'array', // Laravel automatically casts JSON to array
    'start_time' => 'datetime',
];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceEmployees()
    {
        return $this->hasMany(BookingServiceEmployee::class);
    }

}
