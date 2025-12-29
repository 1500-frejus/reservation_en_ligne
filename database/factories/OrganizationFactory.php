<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganizationFactory extends Factory
{
    protected $model = \App\Models\Organization::class;

    public function definition()
    {
        $name = $this->faker->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->randomNumber(3),
            'status' => 'active',
            'contact_email' => $this->faker->companyEmail,
            'settings' => null,
        ];
    }
}
