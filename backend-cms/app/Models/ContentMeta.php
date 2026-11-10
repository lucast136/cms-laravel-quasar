<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMeta extends Model
{
    protected $table = 'content_metas';

    protected $fillable = [
        'content_id',
        'meta_key',
        'meta_value',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
