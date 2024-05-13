<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placeholder extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'name',
        'identification',

    ];

    public function gabarits() {
        return $this->belongsToMany(Gabarit::class, 'contract_models_gabarit');
    }
}
