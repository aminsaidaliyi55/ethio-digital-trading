<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;


class Category extends Model
{
                                    use UUID;

    use HasFactory;
    protected $fillable = [
        'name',
        'tax'
       
    ];
 public function shops()
    {
        return $this->hasMany(Shop::class, 'category_id',);
    } 

    public function product()
    {
        return $this->hasMany(Product::class, 'category_id');
    }


}
