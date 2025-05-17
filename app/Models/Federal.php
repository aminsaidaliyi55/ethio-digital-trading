<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
        use App\Traits\UUID;


class Federal extends Model
{
                                use UUID;

    use HasFactory;
    protected $fillable = [
        'name',
       // Add all other fields that should be mass-assignable
    ];

     public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}
  public function user()
    {
        return $this->hasMany(User::class);
    }

}
