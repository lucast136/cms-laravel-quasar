<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentRevision extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'content_id',
        'user_id',
        'title',
        'sections_snapshot',
        'content_html',
    ];

    protected $casts = [
        'sections_snapshot' => 'array',
        'created_at' => 'datetime',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
