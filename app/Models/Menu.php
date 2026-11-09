<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // Menu links are /pages/{slug}; the admin form only asks for a name.
    use HasFactory, HasSlug;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'slug',
    ];
}
