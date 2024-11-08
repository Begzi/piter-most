<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use DateTime;

class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Создаем ассоциированного пользователя
            'score' => $this->faker->numberBetween(0, 100),
            'created_at' => $this->randomDate(), // Генерация даты
        ];
    }
    protected function randomDate()
    {
        // Выбираем случайный тип времени: день, неделя или месяц
        $interval = $this->faker->randomElement(['day', 'week', 'month']);
        
        // Генерируем дату по выбранному интервалу
        switch ($interval) {
            case 'week':
                $date = new DateTime('monday this week');
                break;
            case 'month':
                $date =  new DateTime('first day of this month');
                break;
            default:
                $date =  new DateTime('today');
        }
        return $date->setTime(0, 0, 0);
    }
}
