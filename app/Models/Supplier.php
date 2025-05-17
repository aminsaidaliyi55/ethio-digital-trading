<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Define fillable fields to allow mass assignment
    protected $fillable = [
        'name', // Add other supplier-related fields here
    ];

    // Define the relationship with the Product model
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }
}
