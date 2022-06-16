<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FriendRequest>
 */
class FriendRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_email' => $this->faker->email(),
            'requestor_id' => $this->faker->randomDigit(),
            'requestor_email' => $this->faker->email(),
            'status' => $this->faker->name()
        ];
    }
}
