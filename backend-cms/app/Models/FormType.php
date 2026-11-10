<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'schema',
    ];

    protected $casts = [
        'schema' => 'array',
    ];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_type', 'code');
    }
}
