<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AvailabilityResource;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Services\Availability\AvailabilityService;
use App\Services\DoctorService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class DoctorsController extends Controller
{
    /**
     * @param DoctorService $doctorService
     * @return AnonymousResourceCollection
     */
    public function index(DoctorService $doctorService): AnonymousResourceCollection
    {
        $doctors = $doctorService->getDoctors();

        return DoctorResource::collection($doctors);
    }

    /**
     * @param Doctor $doctor
     * @param AvailabilityService $availabilityService
     * @return AnonymousResourceCollection
     * @throws RequestException
     * @throws Throwable
     */
    public function availabilities(
        Doctor $doctor,
        AvailabilityService $availabilityService
    ): AnonymousResourceCollection {
        $availabilities = $availabilityService->getByDoctor($doctor);

        return AvailabilityResource::collection($availabilities);
    }
}
