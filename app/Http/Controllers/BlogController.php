<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stephenjude\FilamentBlog\Models\Category;
use Stephenjude\FilamentBlog\Models\Post;

class BlogController extends Controller
{
    //

    public function noticiasIndex(Request $request)
    {

        setlocale(LC_TIME, 'es_ES.UTF-8');

        $category = Category::where('slug', 'noticias')->first();

        if (!$category) {
            $posts = null;
            return view('noticias', compact('posts'));
        }

        $posts = $category->posts()->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $request->get('page', 1))->setPath('');

        if ($posts->isEmpty()) {
            return view('noticias', compact('posts'));
        }


        //dd($posts);
        return view('noticias', compact('posts'));
    }
}
