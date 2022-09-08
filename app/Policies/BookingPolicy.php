<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function cancel(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }
}
