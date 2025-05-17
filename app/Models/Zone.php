<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;


class Zone extends Model
{
            use UUID;

    use HasFactory;
     protected $fillable = [
        'name',
        'region_id', // Just list the column names here
    ];
     public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function woredas()
    {
        return $this->hasMany(Woreda::class);
    }
    
    public function admin()
{
    return $this->belongsTo(User::class);
}

}
