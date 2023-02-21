<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    public function variants_name()
    {
        return $this->hasMany(ProductVariant::class,'variant_id','id');
    }

}
