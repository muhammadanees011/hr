<?php

namespace App\Models;

use App\Models\EclaimType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eclaim extends Model
{
    use HasFactory;

    public function claimType(){
        return $this->belongsTo(EclaimType::class, 'type_id');
    }
}
