<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Frontend',
            'Backend',
            'DevOps',
            'Cybersecurity',

            // Технологии и инструменты
            'HTML',
            'CSS',
            'JavaScript',
            'TypeScript',
            'React',
            'Next.js',
            'TailwindCSS',
            'Figma',
            'PHP',
            'Python',
            'Node.js',
            'Laravel',
            'Go',
            'Java',
            'API',
            'Firebase',
            'GitHub',
            'PostgreSQL',
            'SQL',
            'Docker',
            'AI',
            'Machine Learning',
            'Neural Networks'
                    ];

        foreach ($tags as $name) {
            // Генерация уникального slug
            $slug = match ($name) {
                'C++' => 'cpp',
                'C#' => 'csharp',
                'Node.js' => 'nodejs',
                'Next.js' => 'nextjs',
                default => Str::slug($name)
            };

            Tag::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'slug' => $slug]
            );
        }
    }
}
