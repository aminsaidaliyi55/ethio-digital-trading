<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles; // Make sure to import this trait
    use App\Traits\UUID;


class Shop extends Model
{
                use UUID;

    use HasRoles;
    use HasFactory;
 // Shop model example
protected $fillable = [
    'name', 'owner_id', 'status', 'latitude', 'longitude', 'phone', 'TIN', 'opening_hours', 
    'total_capital', 'website', 'category_id', 'shop_license_path', 'woreda_id', 'kebele_id', 'zone_id',
];


    // Assuming owners are stored as a comma-separated string of user IDs
// Shop.php (Shop Model)
         public function federal()
    {
        return $this->belongsTo(Federal::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
     public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }  
    public function kebele()
    {
        return $this->belongsTo(Kebele::class);
    }
public function owner()
{
    return $this->belongsTo(User::class,'owner_id'); // Assuming owner_id is the foreign key in the shops table
}
public function shop()
{
    return $this->hasMany(Product::class); // Assuming owner_id is the foreign key in the shops table
}

public function category()
{
    return $this->belongsTo(Category::class,'category_id');
}
     public function products()
    {
        return $this->hasMany(Product::class);
    }

// public function owners()
//     {
//         return $this->hasMany(Owner::class); // Assuming a shop has many owners
//     }

}

