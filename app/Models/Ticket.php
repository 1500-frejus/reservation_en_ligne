<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\HasOrganizationScope;

class Ticket extends Model
{
    use HasOrganizationScope;

    protected $fillable = ['reservation_id', 'qr_code', 'pdf_url', 'organization_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'reservation_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
