<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, UUID;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'federal_id',
        'region_id',
        'zone_id',
        'woreda_id',
        'kebele_id' // Added kebele_id properly here
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
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

    public function shops()
    {
        return $this->hasMany(Shop::class, 'user_id'); // Corrected the foreign key, should be user_id
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'owner_id'); // Assuming owner_id is the foreign key in products table
    }
    public function approved_by()
    {
        return $this->hasMany(Orders::class,'approved_by');
    }
   
    // Scopes
    public function scopeRole($query, $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('name', $role);
        });
    }

    // Role level function
    public function getRoleLevel()
    {
        $roles = [
            'Super Admin' => 1,
            'Admin' => 2,
            'FederalAdmin' => 3,
            'RegionalAdmin' => 4,
            'ZoneAdmin' => 5,
            'WoredaAdmin' => 6,
            'KebeleAdmin' => 7,
            'Owners' => 8,
            'Customers' => 9,
        ];

        // Get the first role (if a user can have multiple roles, adapt accordingly)
        $userRole = $this->getRoleNames()->first();

        // Return the role level, defaulting to 9 (Customers) if the role is unrecognized
        return $roles[$userRole] ?? 9;
    }
}
