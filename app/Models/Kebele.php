<?php
// app/Models/Kebele.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
        use App\Traits\UUID;


class Kebele extends Model
{
                            use UUID;

    use HasFactory;

    protected $fillable = ['woreda_id', 'name'];

    public function woreda()
    {
        return $this->belongsTo(Woreda::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function owners()
    {
        return $this->hasMany(User::class);
    }
    public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}


    
}
