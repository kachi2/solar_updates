<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'projects';

    /**
     * Projects are titled, not named: /project/{slug-from-title}.
     */
    public function slugSource(): string
    {
        return 'title';
    }

    protected $fillable = [
        'title',
        'content',
        'image',
        'images',
    ];
}
