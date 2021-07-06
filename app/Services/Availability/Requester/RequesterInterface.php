<?php

declare(strict_types=1);

namespace App\Services\Availability\Requester;

use App\Models\Doctor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Throwable;

interface RequesterInterface
{
    /**
     * @param Doctor $doctor
     * @return Collection|LengthAwarePaginator
     * @throws RequestException
     * @throws Throwable
     */
    public function getAvailabilities(Doctor $doctor): Collection|LengthAwarePaginator;

    /**
     * @param string $doctorAgenda
     * @return bool
     */
    public function canHandle(string $doctorAgenda): bool;
}
