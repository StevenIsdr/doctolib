<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $table = 'demandes';

    protected $fillable = ['raison',
        'details',
        'date',
        'status',
        'demandeur_id',
        'docteur_id'];

    public function patient(){
        return $this->hasOne(User::class,'id','demandeur_id');
    }

    public function doc(){
        return $this->hasOne(User::class,'id','docteur_id');
    }
}
