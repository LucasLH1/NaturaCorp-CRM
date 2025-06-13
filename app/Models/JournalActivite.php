<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

