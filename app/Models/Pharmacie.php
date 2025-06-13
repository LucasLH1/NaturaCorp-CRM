<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'siret',
        'email',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'statut',
        'derniere_prise_contact',
        'commercial_id',
    ];

    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function documents()
    {
        return $this->hasMany(DocumentJoint::class);
    }
}
