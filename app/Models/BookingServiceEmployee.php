<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingServiceEmployee extends Model
{
    protected $table = 'booking_service_employee';
    protected $fillable = ['booking_id','service_id','employee_id','price'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
