<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID; // Ensure you have this trait for UUID
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
    'shop_id',
    'name',
    'description',
    'stock_in',
    'profit',
    'profit_percentage',
    'stock_out',
    'stock_quantity', // This can be calculated if needed
    'purchased_price',
    'selling_price',
    'sku',
    'category_id',
    'stock_in_date',
    'image', // Ensure image is included for uploads
    'sales_tax', // Assuming you're tracking sales tax
    'tax', // If necessary for calculations
    'status', // To track product status
    // Additional fields can be added here based on your requirements
];

    // Relationship with Shop
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with Orders
    public function orders()
{
    return $this->hasMany(Order::class);
}

    // Relationship with Carts
    public function carts()
    {
        return $this->belongsToMany(Cart::class)->withPivot('quantity');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
