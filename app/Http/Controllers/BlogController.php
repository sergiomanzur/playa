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
            return redirect()->back()->with('error', 'Category not found');
        }

        $posts = $category->posts()->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $request->get('page', 1))->setPath('');

        if ($posts->isEmpty()) {
            return redirect()->back()->with('error', 'No posts found for this category');
        }


        //dd($posts);
        return view('noticias', compact('posts'));
    }
}
