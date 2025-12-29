<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    protected $fillable = ['tache', 'statut', 'date_execution'];

    protected $casts = [
        'date_execution' => 'datetime',
    ];
}
