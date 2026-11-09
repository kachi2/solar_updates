<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, HasImage, HasSlug;

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

    protected function imageFolder(): string
    {
        return Media::PROJECTS;
    }
}
