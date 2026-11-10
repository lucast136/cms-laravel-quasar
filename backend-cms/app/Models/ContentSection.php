<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSection extends Model
{
    protected $fillable = [
        'content_id',
        'component_type_id',
        'component_name',
        'sort_order',
        'is_active',
        'settings',
        'data',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'settings' => 'array',
        'data' => 'array',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function componentType()
    {
        return $this->belongsTo(ComponentType::class);
    }
}
