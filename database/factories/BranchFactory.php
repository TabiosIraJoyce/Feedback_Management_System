<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['active', 'inactive']);

        return [
            'name' => $this->faker->company,
            'code' => $this->faker->unique()->bothify('BR-???'),
            'slug' => Str::slug($this->faker->company),
            'description' => $this->faker->sentence(10),
            'business_type_id' => null,
            'address' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional()->safeEmail(),
            'logo' => null,
            'qr_token' => Str::upper(Str::random(24)),
            'status' => $status,
            'is_active' => $status === 'active',
        ];
    }
}
