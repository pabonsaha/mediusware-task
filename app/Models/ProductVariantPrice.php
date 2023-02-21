<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    public function product_variant_one_relation()
    {
        return $this->hasOne(ProductVariant::class,'id','product_variant_one');
    }
    public function product_variant_two_relation()
    {
        return $this->hasOne(ProductVariant::class,'id','product_variant_two');
    }
    public function product_variant_three_relation()
    {
        return $this->hasOne(ProductVariant::class,'id','product_variant_three');
    }
}
