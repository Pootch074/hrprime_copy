<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  protected $model = User::class;

  /**
   * The current password being used by the factory.
   */
  protected static ?string $password = null;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'employee_id' => $this->faker->unique()->numerify('EMP###'),
      'first_name' => $this->faker->firstName(),
      'middle_name' => $this->faker->lastName(),
      'last_name' => $this->faker->lastName(),
      'extension_name' => $this->faker->randomElement([null, 'Jr.', 'Sr.', 'III']),

      // Foreign key references (adjust if you have seeders)
      'employment_status_id' => 1,
      'division_id' => 1,
      'section_id' => 1,

      'username' => $this->faker->unique()->userName(),
      'email' => $this->faker->unique()->safeEmail(),
      'email_verified_at' => now(),
      'password' => static::$password ??= Hash::make('password'),
      'profile_image' => null,
      'remember_token' => Str::random(10),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn(array $attributes) => [
      'email_verified_at' => null,
    ]);
  }
}
