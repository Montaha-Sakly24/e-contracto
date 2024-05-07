<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['title','content','status','signature','model_id'];


    public function contractModel()
    {
        return $this->belongsTo(ContractModel::class,'model_id');
    }
}
