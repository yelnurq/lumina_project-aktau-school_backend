<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\News;

class RssController extends Controller
{
    public function feed()
    {
        $articles = News::latest()->take(20)->get();

        $rssFeed = view('rss.feed', compact('articles'));

        return response($rssFeed, 200)
    ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
    public function sitemap()
    {
        $news = News::all(); // или с пагинацией, если много
        $xml = view('sitemap', compact('news'));

        return response($xml, 200)
                ->header('Content-Type', 'application/xml');
    }
}
