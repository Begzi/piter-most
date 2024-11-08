<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Board;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Создание 15 пользователей
        User::factory(15)->create();

        // Создание 25 записей к Board с ассоциацией к User
        Board::factory(25)->create();
    }
}
