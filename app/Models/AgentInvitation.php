<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AgentInvitation extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'email', 'name', 'token', 'expires_at', 'accepted_at'];

    protected $dates = ['expires_at', 'accepted_at'];

    public static function generateToken(): string
    {
        return Str::random(40);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
