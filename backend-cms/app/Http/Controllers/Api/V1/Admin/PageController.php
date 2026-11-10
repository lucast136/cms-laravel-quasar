<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Content;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;

class PageController extends Controller
{
    protected $model = Content::class;

    /**
     * Filtra únicamente contenidos de tipo página
     */
    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);
        return $query->where('type', 'page');
    }

    /**
     * Campos habilitados para búsqueda rápida
     */
    public function searchableBy(): array
    {
        return ['title', 'slug', 'excerpt'];
    }

    /**
     * Campos habilitados para filtros (?filter[status]=published)
     */
    public function filterableBy(): array
    {
        return ['status', 'template', 'type'];
    }

    /**
     * Campos habilitados para ordenamiento (?sort=-updated_at)
     */
    public function sortableBy(): array
    {
        return ['title', 'sort_order', 'updated_at', 'created_at'];
    }

    /**
     * Relaciones disponibles para eager loading (?include=sections,author)
     */
    public function includes(): array
    {
        return ['sections', 'sections.componentType', 'author', 'metas'];
    }

    /**
     * Hook antes de crear: asegurar autor y tipo
     */
    protected function beforeStore(Request $request, $entity)
    {
        $entity->type = 'page';
        if (auth()->check()) {
            $entity->author_id = auth()->id();
        } else {
            $entity->author_id = 1;
        }
    }
}
