<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Doctor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AvailabilityService
{
    /**
     * @param Doctor $doctor
     * @return LengthAwarePaginator
     */
    public function getByDoctor(Doctor $doctor): LengthAwarePaginator
    {
        return $doctor->availabilities()->paginate();
    }
}
