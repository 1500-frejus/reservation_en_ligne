<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\HasOrganizationScope;

class Schedule extends Model
{
    use HasFactory;
    use HasOrganizationScope;

    protected $fillable = ['route_id','departure_at','price','seats_available','organization_id'];

    protected $casts = [
        'departure_at' => 'datetime',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
