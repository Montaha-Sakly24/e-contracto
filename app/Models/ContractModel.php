<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractModel extends Model
{
    use HasFactory;

    protected $fillable = ['title','description', 'content','category_id'];

    public function category()
    {
        return $this->belongsTo(ContractCategory::class, 'category_id');
    }

    public function gabarits() {
        return $this->belongsToMany(Gabarit::class, 'contract_models_gabarit');
    }
}
