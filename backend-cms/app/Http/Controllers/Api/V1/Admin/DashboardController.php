<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComponentType;
use App\Models\Content;
use App\Models\FormSubmission;
use App\Models\Media;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalPages = Content::where('type', 'page')->count();
        $totalPosts = Content::where('type', 'post')->count();
        $totalComponentTypes = ComponentType::where('is_active', true)->count();
        $totalMedia = Media::count();
        $totalLeads = FormSubmission::count();

        $recentPages = Content::where('type', 'page')
            ->withCount('sections')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'counts' => [
                    'pages' => $totalPages,
                    'posts' => $totalPosts,
                    'component_types' => $totalComponentTypes,
                    'media' => $totalMedia,
                    'leads' => $totalLeads,
                ],
                'recent_pages' => $recentPages,
            ],
        ]);
    }
}
