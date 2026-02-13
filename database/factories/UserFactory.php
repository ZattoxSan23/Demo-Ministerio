<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * UserFactory - Factory para generar usuarios de prueba
 */
class UserFactory extends Factory
{
    /**
     * Nombre del modelo asociado
     */
    protected $model = User::class;

    /**
     * Define el estado por defecto del modelo
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'dni' => $this->faker->unique()->numerify('########'),
            'telefono' => $this->faker->phoneNumber(),
            'activo' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Estado: Usuario inactivo
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }

    /**
     * Estado: Email no verificado
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Estado: Admin
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin ' . $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
        ]);
    }
}
