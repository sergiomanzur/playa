<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stephenjude\FilamentBlog\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('show');
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('post.show', compact('post'));
    }

}
