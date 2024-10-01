<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class position extends Model
{
    use HasFactory;

    protected $table = 'products';

    public function productGroup(){
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function productCategory(){
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function productAlbums(){
        return $this->hasOne(Album::class, 'product_id', 'id');
    }

    public function productStoks(){
        return $this->belongsTo(Stocks::class, 'type_id');
    }



}
