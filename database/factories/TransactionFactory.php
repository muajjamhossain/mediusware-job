<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'transaction_type' => $this->faker->randomElement(array_column(TransactionTypeEnum::cases(), 'value')),
            'amount' => (int) $this->faker->numberBetween(20, 50),
            'fee' => (int) $this->faker->numberBetween(3, 40),
            'date' => $this->faker->date,
        ];
    }
}
