<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'score',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Убедитесь, что это правильное имя поля внешнего ключа
    }
}
