<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{

    use hasFactory;
    protected $fillable = ['nom', 'tarif_unitaire', 'stock', 'is_actif'];

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
            ->withPivot('quantite', 'prix_unitaire')
            ->withTimestamps();
    }

    public function decrementStock(int $quantite): bool
    {
        if ($this->stock < $quantite) {
            return false;
        }

        $this->stock -= $quantite;
        $this->save();

        return true;
    }
}
