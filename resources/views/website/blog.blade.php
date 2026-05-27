@extends('layouts.app')

@section('title', 'Blog | LDC Courier')

@section('body_class', 'blog archive category category-uncategorized wp-theme-inspiro elementor-default elementor-kit-1042')

@section('content')
<div class="ldc-blog-archive" style="max-width:1200px;margin:0 auto;padding:60px 20px;font-family:Poppins,sans-serif;">
    <h1 style="font-size:40px;margin-bottom:24px;color:#092370;">Blog</h1>
    <div class="ldc-blog-list">
        @foreach ($posts as $post)
            <article style="margin-bottom:32px;padding-bottom:32px;border-bottom:1px solid #e5e5e5;">
                <h2 style="font-size:24px;margin:0 0 8px;">
                    <a href="{{ $post['url'] }}" style="color:#092370;text-decoration:none;">{{ $post['title'] }}</a>
                </h2>
                <time datetime="{{ $post['date'] }}" style="color:#666;font-size:14px;">{{ $post['date'] }}</time>
                <p style="margin-top:12px;color:#333;line-height:1.6;">{{ $post['excerpt'] }}</p>
            </article>
        @endforeach
    </div>
</div>
@endsection
