<?php

declare(strict_types=1);

namespace App\Services\Availability;

use App\Models\Doctor;
use App\Services\Availability\Requester\RequesterInterface;
use Exception;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Throwable;

class AvailabilityService
{
    /**
     * @var RewindableGenerator
     */
    private $requesters;

    /**
     * AvailabilityService constructor.
     * @param RewindableGenerator $requesters
     */
    public function __construct(RewindableGenerator $requesters)
    {
        $this->requesters = $requesters;
    }

    /**
     * @param Doctor $doctor
     * @return LengthAwarePaginator|Collection
     * @throws RequestException
     * @throws Throwable
     */
    public function getByDoctor(Doctor $doctor)
    {
        // Hard to come up with "perfect solution" with too many questions
        // Do we need pagination?
        // Will external APIs have pagination?
        // etc.
        $availabilityRequester = $this->getAvailabilityRequester($doctor);

        return $availabilityRequester->getAvailabilities($doctor);
    }

    /**
     * @param Doctor $doctor
     * @return RequesterInterface
     * @throws Exception
     */
    private function getAvailabilityRequester(Doctor $doctor): RequesterInterface
    {
        /** @var RequesterInterface $requester */
        foreach ($this->requesters as $requester) {
            if ($requester->canHandle($doctor->agenda)) {
                return $requester;
            }
        }

        throw new Exception('Availability requester not found.');
    }
}
