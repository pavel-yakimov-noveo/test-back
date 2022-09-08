<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Doctor::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => "Dr. " . $this->faker->firstName() . " " . $this->faker->lastName(),
            'agenda' => Doctor::AGENDA_DATABASE,
        ];
    }

    /**
     * @param $agenda
     * @return DoctorFactory
     */
    public function withAgenda($agenda): DoctorFactory
    {
        return $this->state(function () use ($agenda) {
            return [
                'agenda' => $agenda,
                'external_agenda_id' => $this->faker->uuid()
            ];
        });
    }
}
