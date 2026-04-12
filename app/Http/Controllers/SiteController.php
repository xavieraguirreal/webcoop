<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Page;
use App\Models\WorkArea;

class SiteController extends Controller
{
    public function home()
    {
        $news = News::published()->latest('published_at')->take(3)->get();
        $workAreas = WorkArea::where('is_active', true)->orderBy('sort_order')->get();

        return view('site.home', compact('news', 'workAreas'));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Vista especial para la timeline de historia
        if ($slug === 'historia') {
            return view('site.page-historia', compact('page'));
        }

        return view('site.page', compact('page'));
    }

    public function newsIndex()
    {
        $featured = News::published()->featured()->latest('published_at')->first();
        $news = News::published()->latest('published_at')->paginate(9);
        $categories = \App\Models\Category::withCount('news')->get();

        return view('site.news.index', compact('news', 'featured', 'categories'));
    }

    public function newsShow($slug)
    {
        $article = News::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $related = News::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('site.news.show', compact('article', 'related'));
    }

    public function newsByCategory($slug)
    {
        $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
        $news = News::published()->where('category_id', $category->id)->latest('published_at')->paginate(9);
        $categories = \App\Models\Category::withCount('news')->get();

        return view('site.news.index', compact('news', 'category', 'categories'))->with('featured', null);
    }

    public function workAreas()
    {
        $areas = WorkArea::where('is_active', true)->orderBy('sort_order')->get();
        $grouped = $areas->groupBy('group');
        return view('site.work-areas', compact('areas', 'grouped'));
    }

    public function workAreaShow($slug)
    {
        $area = WorkArea::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('site.work-area-show', compact('area'));
    }

    public function contact()
    {
        return view('site.contact');
    }
}
