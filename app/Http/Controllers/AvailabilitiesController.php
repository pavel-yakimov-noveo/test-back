<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\AvailabilityResource;
use App\Models\Doctor;
use App\Services\AvailabilityService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AvailabilitiesController extends Controller
{
    /**
     * @param Doctor $doctor
     * @param AvailabilityService $availabilityService
     * @return AnonymousResourceCollection
     */
    public function index(
        Doctor $doctor,
        AvailabilityService $availabilityService
    ): AnonymousResourceCollection {
        $availabilities = $availabilityService->getByDoctor($doctor);

        return AvailabilityResource::collection($availabilities);
    }
}
