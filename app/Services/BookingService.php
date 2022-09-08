<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

class BookingService
{
    /**
     * @param User $user
     * @param string $orderDir
     * @return LengthAwarePaginator
     */
    public function getBookings(User $user, string $orderDir = 'ASC'): LengthAwarePaginator
    {
        return $user
            ->bookings()
            ->where('date', '>=', now())
            ->orderBy('date', $orderDir)
            ->paginate();
    }

    /**
     * @param User $user
     * @param array $bookingData
     * @return Booking
     * @throws Throwable
     */
    public function create(User $user, array $bookingData): Booking
    {
        $booking = new Booking();

        $booking->fill($bookingData);
        $booking->status = Booking::STATUS_CONFIRMED;
        $booking->user_id = $user->id;

        try {
            $booking->saveOrFail();
        } catch (Exception $e) {
            // this part depends on how we plan to handle exceptions
            // i.e. maybe we want some custom exception with specific response defined
            throw $e;
        }

        return $booking;
    }

    /**
     * @param Booking $booking
     * @throws Throwable
     */
    public function cancel(Booking $booking)
    {
        $booking->status = Booking::STATUS_CANCELED;

        try {
            $booking->saveOrFail();
        } catch (Exception $e) {
            // this part depends on how we plan to handle exceptions
            // i.e. maybe we want some custom exception with specific response defined
            throw $e;
        }
    }
}
