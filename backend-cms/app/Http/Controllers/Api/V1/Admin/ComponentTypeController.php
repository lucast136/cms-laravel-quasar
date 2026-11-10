<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\ComponentType;
use Orion\Http\Controllers\Controller;

class ComponentTypeController extends Controller
{
    protected $model = ComponentType::class;

    public function searchableBy(): array
    {
        return ['name', 'code', 'description'];
    }

    public function filterableBy(): array
    {
        return ['category', 'is_active', 'is_system'];
    }

    public function sortableBy(): array
    {
        return ['name', 'code', 'category'];
    }
}
