<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Программирование',
            'Образование',
            'Технологии',
            'Искусственный интеллект',
            'Кибербезопасность',
            'Стартапы и бизнес',
            'Облачные технологии',
            'UI/UX дизайн',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
