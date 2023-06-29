<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepeatedMessage extends Model
{
    protected $table = 'repeated_messages';
    use HasFactory;

    protected $fillable = [
        'content',
        'contact_id'
        // Otros campos asignables masivamente aquí
    ];
    
}
