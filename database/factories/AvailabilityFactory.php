<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Availability::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+2 month');

        return [
            'start' => $start,
            'end' => Carbon::parse($start)->addMinutes(30)
        ];
    }
}
