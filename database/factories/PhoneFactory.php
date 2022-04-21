<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user=User::inRandomOrder()->first();
        return [
             'number'=>$this->faker->phoneNumber(),
             'type'=>$this->faker->randomElement(['mobile','home','work']),
             'user_id'=>$user->id
        ];
    }
}
