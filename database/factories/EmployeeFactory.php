<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'middle_name' => fake()->name(),
            'city_id' =>  City::factory(),
            'department_id' =>  Department::factory(),
            'zip_code' => fake()->postcode(),
            'birth_date' => fake()->dateTime('now'),
            'date_hired' => fake()->dateTime('now')
        ];
    }
}
