<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['reservation_id', 'montant', 'mode', 'statut'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'reservation_id');
    }
}
