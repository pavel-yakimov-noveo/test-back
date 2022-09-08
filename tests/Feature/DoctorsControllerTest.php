<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DoctorsControllerTest extends TestCase
{
    use RefreshDatabase;

    public const DOCTORS_RESPONSE_STRUCTURE = [
        'data' => [
            [
                'id',
                'name',
            ],
        ],
    ];

    public const AVAILABILITIES_RESPONSE_STRUCTURE = [
        'data' => [
            [
                'start',
            ],
        ],
        'links',
        'meta',
    ];

    /**
     * @return void
     */
    public function test_doctors_index_endpoint()
    {
        $testDoctorsCount = 5;
        Doctor::factory()
            ->count($testDoctorsCount)
            ->create();

        $response = $this->getJson(route('api.doctors.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::DOCTORS_RESPONSE_STRUCTURE);
        $response->assertJsonCount($testDoctorsCount, 'data');
    }

    /**
     * @return void
     */
    public function test_doctors_availabilities_endpoint()
    {
        $availablitiesCount = 5;
        $doctor = Doctor::factory()
            ->has(Availability::factory()->count($availablitiesCount))
            ->create();

        $response = $this->getJson(
            route('api.doctors.availabilities', [
                'doctor' => $doctor->id,
            ])
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::AVAILABILITIES_RESPONSE_STRUCTURE);
        $response->assertJsonCount($availablitiesCount, 'data');
    }

    /**
     * @return void
     */
    public function test_only_specified_doctor_availabilites_are_listed()
    {
        $availablitiesCount = 5;
        $doctor = Doctor::factory()
            ->has(Availability::factory()->count($availablitiesCount))
            ->create();

        $otherDoctorsCount = 2;
        Doctor::factory()
            ->count($otherDoctorsCount)
            ->has(Availability::factory()->count($availablitiesCount))
            ->create();

        $this->assertGreaterThan($availablitiesCount, Availability::count());

        $response = $this->getJson(
            route('api.doctors.availabilities', [
                'doctor' => $doctor->id,
            ])
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(self::AVAILABILITIES_RESPONSE_STRUCTURE);
        $response->assertJsonCount($availablitiesCount, 'data');
    }

    /**
     * @return void
     */
    public function test_not_found_doctor_availabilities()
    {
        $response = $this->getJson(
            route('api.doctors.availabilities', [
                'doctor' => 'some-fake-id',
            ])
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
