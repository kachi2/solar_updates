<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory, HasImage;

    protected $table = 'sliders';

    protected $fillable = [
        'name',
        'image',
    ];

    protected function imageFolder(): string
    {
        return Media::SLIDERS;
    }
}
