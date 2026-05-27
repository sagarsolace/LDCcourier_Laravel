<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Cache::remember('website.blog.posts', now()->addHours(6), static function () {
            return [
                [
                    'title' => 'Blog',
                    'slug' => 'blog',
                    'excerpt' => 'Latest news and updates from LDC Courier.',
                    'date' => '2026-01-31',
                    'url' => '/blog',
                ],
            ];
        });

        return view('website.blog', ['posts' => $posts]);
    }
}
