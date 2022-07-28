<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_code',
        'name',
        'price',
        'purchase_price',
        'quantity',
        'description',
        'product_type_id',
        'image',
        'deleted'
    ];

    /**
     * Get the user associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
    }
}
