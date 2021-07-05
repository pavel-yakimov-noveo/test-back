<?php

declare(strict_types=1);

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Doctor
 * @package App\Models
 * @mixin Eloquent
 */
class Doctor extends Model
{
    use HasFactory;

    const AGENDA_DATABASE = 'database';
    const AGENDA_DOCTOLIB = 'doctolib';
    const AGENDA_CLICRDV = 'clicrdv';

    /**
     * @return HasMany
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
