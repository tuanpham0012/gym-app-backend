<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CoachLevel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coach>
 */
class CoachFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? '1',
            'coach_level_id' => CoachLevel::query()->inRandomOrder()->value('id') ?? '1',
            'experience' => 'Abc',
            'introduction' => 'Def',
            'phone' => '0123456789',
            'timeline' => '6h-12h, 15h-20h',
            'character' => 'ghi',
            'email' => 'trungthanh@gmail.com'.Str::random(2),
            'avatar' => '',
            'deleted' => 0,
        ];
    }
}
