<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    use HasFactory;

    protected $fillable = [
        'chat_id',
        'content',
        'status',
        'forwarded_message'
        // Otros campos asignables masivamente aquí
    ];
}
