<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['name','origin','destination','duration_minutes','capacity'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
