<?php

declare(strict_types=1);

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Booking
 * @package App\Models
 * @mixin Eloquent
 */
class Booking extends Model
{
    use HasFactory;

    public const STATUSES = [
        self::STATUS_CONFIRMED,
        self::STATUS_CANCELED,
    ];

    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELED = 'canceled';

    /**
     * @var string[]
     */
    protected $fillable = [
        'doctor_id',
        'date',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
