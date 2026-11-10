<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'icon',
        'description',
        'schema',
        'default_data',
        'default_settings',
        'is_system',
        'is_active',
    ];

    protected $casts = [
        'schema' => 'array',
        'default_data' => 'array',
        'default_settings' => 'array',
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(ContentSection::class);
    }
}
