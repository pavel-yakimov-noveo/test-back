<?php

declare(strict_types=1);

namespace App\Services\Availability\Requester;

use App\Models\Doctor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Throwable;

class Database implements RequesterInterface
{
    /**
     * @param string $doctorAgenda
     * @return bool
     */
    public function canHandle(string $doctorAgenda): bool
    {
        return $doctorAgenda === Doctor::AGENDA_DATABASE;
    }

    /**
     * @param Doctor $doctor
     * @return Collection|LengthAwarePaginator
     * @throws RequestException|Throwable
     */
    public function getAvailabilities(Doctor $doctor): Collection|LengthAwarePaginator
    {
        return $doctor->availabilities()
            ->where('start', '>=', now())
            ->paginate();
    }
}
