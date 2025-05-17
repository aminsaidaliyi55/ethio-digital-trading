<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str for generating UUIDs
use App\Models\User; // Import the User model
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Import this for UUID handling

class Orders extends Model
{
    use HasFactory;
    use HasFactory, HasUuids;

    protected $keyType = 'string'; // Set key type to string
    public $incrementing = false; // Disable auto-incrementing

    protected $table = 'orders'; // Ensure this matches your database table name
    protected $fillable = ['user_id', 'product_id', 'quantity', 'total_price', 'approved_by','status']; // Add all fields you want to be mass-assignable


    // Generate UUID when creating a new order
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->id = (string) Str::uuid(); // Generate UUID
        });
    }
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
 
      public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

  
}
