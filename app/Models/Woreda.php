<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    use App\Traits\UUID;

class Woreda extends Model
{

            use UUID;

    use HasFactory;
     protected $fillable = [
        'name',
        'zone_id', // Just list the column names here
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

       public function shop()
    {
        return $this->hasMany(Shop::class);
    } 
    public function kebeles()
{
    return $this->hasMany(Kebele::class);
}
public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}

}

