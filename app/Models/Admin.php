<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'password',
        'login_ip',
        'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'admin_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'admin_id');
    }
}
