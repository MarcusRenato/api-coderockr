<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'profile_picture' => 'profile.jpg',
            'bio' => $this->faker->sentence,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'password' => bcrypt('123456'),
            'remember_token' => Str::random(10),
        ];
    }
}
