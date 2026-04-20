<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Domain;

class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition()
    {
        return [
            'name' => $this->faker->domainName,
            'registrar' => $this->faker->company,
            'expiration_date' => $this->faker->dateTimeBetween('+1 days', '+2 years')->format('Y-m-d'),
            'annual_cost' => $this->faker->randomFloat(2, 0, 200),
            'notes' => null,
            'auto_renew' => $this->faker->boolean(30),
        ];
    }
}
