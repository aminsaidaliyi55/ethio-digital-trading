<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    use App\Traits\UUID;


class Region extends Model
{
                    use UUID;

    use HasFactory;

    // These are the fields that are mass-assignable
    protected $fillable = [
        'name',
        'federal_id',
        'admin_id', // Just list the column names here
    ];

    // Relationship with Federal
    public function federal()
    {
        return $this->belongsTo(Federal::class);
    }

    // Relationship with Zone

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}

}
