<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\FormSubmission;
use App\Models\Term;
use App\Services\ContentService;
use App\Services\MenuService;
use App\Services\SiteSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function __construct(
        protected ContentService $contentService,
        protected SiteSettingService $settingService,
        protected MenuService $menuService
    ) {}

    public function showPage(string $slug): JsonResponse
    {
        // Mapeo amigable de alias (ej. /about -> /nosotros para SEO en español)
        $aliases = [
            'about' => 'nosotros',
            'courses' => 'carreras',
            'instructors' => 'docentes',
            'events' => 'eventos',
            'blog' => 'noticias',
            'contact' => 'contacto',
        ];
        $targetSlug = $aliases[$slug] ?? $slug;

        $page = $this->contentService->getPageBySlug($targetSlug);

        if (!$page || $page->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Página no encontrada o no publicada.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }

    public function listPosts(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 9);
        $search = $request->input('search');
        $category = $request->input('category');

        $posts = $this->contentService->getPosts($perPage, $search, $category);

        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    public function showPost(string $slug): JsonResponse
    {
        $post = Content::with(['author', 'terms.taxonomy', 'metas'])
            ->where('type', 'post')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Artículo no encontrado o no publicado.',
            ], 404);
        }

        // Si el post requiere barra lateral (with-sidebar), adjuntar widgets con límite máximo de 5 ítems
        $recentPosts = Content::where('type', 'post')
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'featured_image', 'published_at', 'created_at']);

        $categories = Term::whereHas('taxonomy', fn ($q) => $q->where('slug', 'category'))
            ->withCount([
                'contents as posts_count' => fn ($q) => $q
                    ->where('type', 'post')
                    ->where('status', 'published'),
            ])
            ->orderBy('sort_order')
            ->limit(5)
            ->get(['id', 'name', 'slug', 'badge_color']);

        $tags = Term::whereHas('taxonomy', fn ($q) => $q->where('slug', 'post_tag'))
            ->orderBy('sort_order')
            ->limit(5)
            ->get(['id', 'name', 'slug']);

        $postData = $post->toArray();
        $postData['sidebar'] = [
            'recent_posts' => $recentPosts,
            'categories' => $categories,
            'tags' => $tags,
        ];

        return response()->json([
            'success' => true,
            'data' => $postData,
        ]);
    }

    public function publicSettings(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->settingService->getPublicSettings(),
        ]);
    }

    public function getMenu(string $location): JsonResponse
    {
        $menu = $this->menuService->getMenuByLocation($location);

        return response()->json([
            'success' => true,
            'data' => $menu,
        ]);
    }

    public function submitForm(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'form_type' => 'required|string',
            'payload' => 'required|array',
        ]);

        $submission = FormSubmission::create([
            'form_type' => $validated['form_type'],
            'payload' => $validated['payload'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Formulario recibido correctamente.',
            'data' => $submission,
        ], 201);
    }
}
