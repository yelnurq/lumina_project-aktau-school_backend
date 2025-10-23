<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $subjects = [
            ['name' => 'HTML/CSS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'JavaScript', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Python', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'PHP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'C++', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Java', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'C#', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Go', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rust', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Алгоритмы и структуры данных', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Базы данных и SQL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Frontend-разработка (React, Vue)', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Backend-разработка (Node.js, Laravel, Django)', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Мобильная разработка (Android, iOS, Flutter)', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Искусственный интеллект', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Кибербезопасность', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Робототехника', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('subjects')->insert($subjects);
    }
}
