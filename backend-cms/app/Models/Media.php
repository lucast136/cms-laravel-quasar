<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'title',
        'alt_text',
        'filename',
        'filepath',
        'disk',
        'mime_type',
        'file_size',
        'dimensions',
    ];

    protected $casts = [
        'dimensions' => 'array',
        'file_size' => 'integer',
    ];

    protected $appends = [
        'url',
    ];

    public function getUrlAttribute(): string
    {
        if (!$this->filepath) {
            return '';
        }
        if (str_starts_with($this->filepath, 'http://') || str_starts_with($this->filepath, 'https://')) {
            return $this->filepath;
        }
        if (str_starts_with($this->filepath, '/storage/')) {
            return url($this->filepath);
        }
        return $this->filepath;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
