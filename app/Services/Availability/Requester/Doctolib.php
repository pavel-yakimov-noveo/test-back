<?php

declare(strict_types=1);

namespace App\Services\Availability\Requester;

use App\Models\Doctor;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Throwable;

class Doctolib implements RequesterInterface
{
    private const URL = "https://tech-test.joovence.dev/api/doctolib/%s/availabilities";
    private const RETRIES = 2;
    private const RETRY_TIMEOUT = 300;

    /**
     * @param string $doctorAgenda
     * @return bool
     */
    public function canHandle(string $doctorAgenda): bool
    {
        return $doctorAgenda === Doctor::AGENDA_DOCTOLIB;
    }

    /**
     * @param Doctor $doctor
     * @return Collection
     * @throws RequestException|Throwable
     */
    public function getAvailabilities(Doctor $doctor): Collection
    {
        /**
         * This method is a subject for splitting into separate methods
         * Since Doctolib and ClicRDV APIs are basically same these requesters are nearly identical
         * In case of different JSON returned back these would have less in common
         * At the moment these are just examples of separation into different services
         */

        $requestUrl = $this->getRequestUrl($doctor);
        $response = Http::retry(self::RETRIES, self::RETRY_TIMEOUT)
            ->get($requestUrl);

        // here we can have custom logic depending on how we want to handle the exception
        $response->throw();

        $responseData = $response->json();

        if (!is_array($responseData)) {
            throw new Exception();
        }

        // here we can explicitly validate and format the data returned
        return collect($responseData)->map(function ($availability) {
            $validator = Validator::make($availability, [
                'start' => 'required|string|date',
            ]);

            if ($validator->fails()) {
                // custom logic for handling such situation
                // for now just an exception
                throw new Exception();
            }

            return (object) $validator->validated();
        });
    }

    /**
     * @param Doctor $doctor
     * @return string
     */
    private function getRequestUrl(Doctor $doctor): string
    {
        return sprintf(
            self::URL,
            $doctor->external_agenda_id
        );
    }
}
