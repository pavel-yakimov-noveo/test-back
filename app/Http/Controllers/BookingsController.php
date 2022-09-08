<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BookingCreateRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class BookingsController extends Controller
{
    /**
     * @param BookingService $bookingService
     * @return AnonymousResourceCollection
     */
    public function index(BookingService $bookingService): AnonymousResourceCollection
    {
        $bookings = $bookingService->getBookings(Auth::user());

        return BookingResource::collection($bookings);
    }

    /**
     * @param BookingCreateRequest $request
     * @param BookingService $bookingService
     * @return BookingResource
     * @throws Throwable
     */
    public function create(
        BookingCreateRequest $request,
        BookingService $bookingService
    ): BookingResource {
        $booking = $bookingService->create(Auth::user(), $request->validated());

        return BookingResource::make($booking);
    }

    /**
     * @param Booking $booking
     * @param BookingService $bookingService
     * @return BookingResource
     * @throws Throwable
     */
    public function cancel(
        Booking $booking,
        BookingService $bookingService
    ): BookingResource {
        $this->authorize('cancel', $booking);

        // Here we change booking status to 'canceled'
        // Is it what was meant in test task spec?
        $bookingService->cancel($booking);

        return BookingResource::make($booking);
    }
}
