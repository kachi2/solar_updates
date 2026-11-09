<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{
    use HasFactory;

    protected $table = 'dispatchers';

    protected $fillable = [
        'name',
        'email',
        'location',
        'phone',
        'city',
        'state',
        'company_id',
    ];
}
