<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{

    use hasFactory;
    protected $fillable = ['nom', 'description', 'categorie', 'tarif_unitaire', 'stock', 'stock_alerte', 'is_actif'];

    protected $casts = ['is_actif' => 'boolean'];

    public function isStockFaible(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->stock_alerte;
    }

    public function isRupture(): bool
    {
        return $this->stock === 0;
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
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
