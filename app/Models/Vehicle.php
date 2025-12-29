<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\HasOrganizationScope;

class Vehicle extends Model
{
    use HasOrganizationScope;

    protected $fillable = ['immatriculation', 'type_id', 'capacite', 'statut', 'organization_id'];

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'type_id');
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
