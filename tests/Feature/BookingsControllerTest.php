<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Http\Response;

class BookingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public const BOOKINGS_LIST_RESPONSE_STRUCTURE = [
        'data' => [
            [
                'id',
                'doctor_id',
                'user_id',
                'date',
                'status',
            ],
        ],
        'links',
        'meta',
    ];

    public const BOOKING_RESPONSE_STRUCTURE = [
        'data' => [
            'id',
            'doctor_id',
            'user_id',
            'date',
            'status',
        ],
    ];

    /**
     * @return void
     */
    public function test_unathorized_response_on_bookings_list_endpoint()
    {
        $response = $this->getJson(route('api.bookings.index'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function test_list_of_user_bookings()
    {
        $bookingsCount = 5;
        $user = User::factory()
            ->has(Booking::factory()->count($bookingsCount))
            ->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.bookings.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::BOOKINGS_LIST_RESPONSE_STRUCTURE);
        $response->assertJsonCount($bookingsCount, 'data');
    }

    /**
     * @return void
     */
    public function test_unathorized_response_on_create_booking_endpoint()
    {
        $response = $this->postJson(route('api.bookings.create'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return void
     */
    public function test_create_booking_endpoint()
    {
        $doctor = Doctor::factory()->create();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->assertEquals(0, Booking::count());

        $bookingData = [
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson(route('api.bookings.create'), $bookingData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(self::BOOKING_RESPONSE_STRUCTURE);

        $this->assertDatabaseHas('bookings', $bookingData);
        $this->assertEquals(1, Booking::count());

        $booking = Booking::first();

        $this->assertTrue($booking->user_id === $user->id);
        $this->assertTrue($booking->status === Booking::STATUS_CONFIRMED);
    }

    /**
     * @return void
     */
    public function test_booking_cancelled()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('api.bookings.cancel', [
            'booking' => $booking->id,
        ]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::BOOKING_RESPONSE_STRUCTURE);

        $booking->refresh();

        $this->assertTrue($booking->status === Booking::STATUS_CANCELED);
    }
}
