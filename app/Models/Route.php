<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\HasOrganizationScope;

class Route extends Model
{
    use HasOrganizationScope;

    protected $fillable = ['depart', 'arrivee', 'horaire', 'duree_minutes', 'prix', 'organization_id'];

    protected $casts = [
        'horaire' => 'datetime',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Scope helper to filter by organization when needed
    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }
}
