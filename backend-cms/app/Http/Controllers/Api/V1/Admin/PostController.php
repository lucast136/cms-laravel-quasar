<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Content;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    protected $model = Content::class;

    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);
        return $query->where('type', 'post');
    }

    public function searchableBy(): array
    {
        return ['title', 'slug', 'excerpt', 'content_html'];
    }

    public function filterableBy(): array
    {
        return ['status'];
    }

    public function sortableBy(): array
    {
        return ['published_at', 'created_at', 'updated_at', 'title'];
    }

    public function includes(): array
    {
        return ['author', 'terms', 'comments'];
    }

    protected function beforeStore(Request $request, $entity)
    {
        $entity->type = 'post';
        if (auth()->check()) {
            $entity->author_id = auth()->id();
        } else {
            $entity->author_id = 1;
        }
    }
}
