<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'author_id',
        'type',
        'title',
        'slug',
        'excerpt',
        'content_html',
        'featured_image',
        'status',
        'template',
        'comment_status',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function sections()
    {
        return $this->hasMany(ContentSection::class)->orderBy('sort_order');
    }

    public function metas()
    {
        return $this->hasMany(ContentMeta::class);
    }

    public function revisions()
    {
        return $this->hasMany(ContentRevision::class)->latest();
    }

    public function terms()
    {
        return $this->belongsToMany(Term::class, 'content_term');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePages($query)
    {
        return $query->where('type', 'page');
    }

    public function scopePosts($query)
    {
        return $query->where('type', 'post');
    }

    public function scopeCourses($query)
    {
        return $query->where('type', 'course');
    }
}
