<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = ['nom', 'code_departement', 'user_id'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function pharmacies()
    {
        return $this->hasMany(Pharmacie::class);
    }

}
