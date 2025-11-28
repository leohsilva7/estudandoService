<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Temperature extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'city',
        'temperature_fahrenheit',
    ];

    protected $hidden = [
        'created_at',
    ];
}
