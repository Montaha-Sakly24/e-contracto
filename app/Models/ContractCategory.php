<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractCategory extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'title',
        'description',


    ];

    public function models()
    {
        return $this->hasMany(ContractModel::class,'category_id');
    }

}
