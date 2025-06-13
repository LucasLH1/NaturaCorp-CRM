<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacie_id',
        'date_commande',
        'statut',
        'quantite',
        'tarif_unitaire',
        'observations',
    ];

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }
}
