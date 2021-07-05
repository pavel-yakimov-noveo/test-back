<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\DoctorResource;
use App\Services\DoctorService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
}
