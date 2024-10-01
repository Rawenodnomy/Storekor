<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'user_id',
        'version_id',
        'count',
    ];

    public function basketProduct(){
        return $this->belongsTo(position::class, 'position_id');
    }


    public function basketVersion(){
        return $this->belongsTo(Version::class, 'version_id');
    }


}
