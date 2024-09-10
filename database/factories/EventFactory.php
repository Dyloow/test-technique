<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // dd($this->faker->dateTimeThisYear());
        $start = $this->faker->dateTimeThisYear();

        $end = $start->modify('+1 day');
        return [
            'title'     => $this->faker->jobTitle(),
            'starts_at' => $start,
            'ends_at'   => $end,
        ];
    }
}
