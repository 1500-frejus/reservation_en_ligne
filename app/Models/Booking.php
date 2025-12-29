<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\HasOrganizationScope;

class Booking extends Model
{
    use HasFactory;
    use HasOrganizationScope;

    protected $fillable = ['user_id', 'schedule_id', 'passenger_name', 'passenger_email', 'seats', 'status', 'qr_token', 'payment_status', 'route_id', 'organization_id'];

    protected $casts = [
        'seats' => 'integer',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'reservation_id');
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'reservation_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
