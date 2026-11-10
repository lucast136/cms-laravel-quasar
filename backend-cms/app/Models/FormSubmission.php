<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'form_type',
        'payload',
        'ip_address',
        'user_agent',
        'status',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function formType()
    {
        return $this->belongsTo(FormType::class, 'form_type', 'code');
    }
}
