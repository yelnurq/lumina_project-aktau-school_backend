<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
public function index(Request $request)
{
    $query = News::with(['category'])
        ->when($request->filled('category'), fn($q) =>
            $q->whereHas('category', fn($qq) =>
                $qq->where('slug', $request->category)
            )
        )
        ->when($request->filled('date'), fn($q) =>
            $q->whereDate('created_at', $request->date)
        )
        ->when($request->filled('sort'), function ($q) use ($request) {
            $q->orderBy('created_at', $request->sort === 'oldest' ? 'asc' : 'desc');
        }, fn($q) => $q->latest());

    $news = $query->paginate(10);

    return response()->json([
        'data' => $news->items(),
        'meta' => [
            'current_page' => $news->currentPage(),
            'last_page'    => $news->lastPage(),
            'per_page'     => $news->perPage(),
            'total'        => $news->total(),
        ],
    ]);
}

public function search(Request $request)
{
    $query = $request->input('q');

    if (!$query) {
        return response()->json([
            'data' => [],
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
            ]
        ]);
    }

    $news = News::with(['category', 'tags'])
        ->where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%");
        })
        ->latest()
        ->paginate(1);

    return response()->json([
        'data' => $news->items(),
        'meta' => [
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'total' => $news->total(),
        ]
    ]);
}
public function home()
{
    $latest = News::latest()->take(3)->get();


    return response()->json([
        'latest'     => $latest,
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title'        => 'required|string|max:255',
        'content'      => 'required|string',
        'category_id'  => 'required|exists:categories,id',
        'excerpt'      => 'required|string',
        'reading_time' => 'nullable|string',
        'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Загружаем картинку
    $validated['image'] = $request->hasFile('image')
        ? $request->file('image')->store('news_images', 'public')
        : null;

    // Создаём новость
    $news = News::create([
        'title'        => $validated['title'],
        'slug'         => Str::slug($validated['title']),
        'content'      => $validated['content'],
        'excerpt'      => $validated['excerpt'],
        'reading_time' => $validated['reading_time'],
        'image'        => $validated['image'],
        'category_id'  => $validated['category_id'],
    ]);



    return response()->json(
        $news->load(['category']),
        201
    );
}

public function show($slug)
{
    $news = News::with(['category'])
        ->where('slug', $slug)
        ->firstOrFail();

    // увеличиваем просмотры, но не трогаем updated_at
    $news->timestamps = false;
    $news->increment('views');
    $news->timestamps = true;

    // Похожие — из той же категории, если есть
    $relatedNews = News::where('id', '!=', $news->id)
        ->when($news->category_id, fn($q) =>
            $q->where('category_id', $news->category_id)
        )
        ->inRandomOrder()
        ->take(4)
        ->get(['id', 'title', 'slug', 'image', 'created_at']);

    return response()->json([
        'id'           => $news->id,
        'title'        => $news->title,
        'slug'         => $news->slug,
        'image'        => $news->image,
        'excerpt'      => $news->excerpt,
        'reading_time' => $news->reading_time,
        'content'      => $news->content,
        'created_at'   => $news->created_at,
        'category'     => $news->category?->name,
        'category_slug'=> $news->category?->slug,
        'related'      => $relatedNews,
    ]);
}


    public function update(Request $request, $slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            if ($news->image) Storage::disk('public')->delete($news->image);
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $news->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $imagePath,
            'category_id' => $request->category_id,
        ]);


        return response()->json($news->load(['category', 'tags']));
    }

    public function destroy($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->tags()->detach();
        $news->delete();

        return response()->json(['message' => 'Новость удалена']);
    }
public function indexWithViews(Request $request)
{
    $sortBy = $request->query('sort_by', 'views'); // 'views' или 'date'
    $sortDir = $request->query('sort_dir', 'desc'); // 'asc' или 'desc'
    $perPage = $request->query('per_page', 30);

    // Проверка допустимых значений
    $sortBy = in_array($sortBy, ['views', 'date']) ? $sortBy : 'views';
    $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';

    $orderColumn = $sortBy === 'date' ? 'created_at' : 'views';

    $news = News::orderBy($orderColumn, $sortDir)
        ->select('id', 'title', 'slug', 'views', 'created_at')
        ->paginate($perPage);

    $totalViews = News::sum('views');
    $totalCount = $news->total();

    return response()->json([
        'news' => $news->items(),
        'total_views' => $totalViews,
        'total_count' => $totalCount,
        'current_page' => $news->currentPage(),
        'last_page' => $news->lastPage()
    ]);
}


}
