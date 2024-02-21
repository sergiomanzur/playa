<?php

$months = [
    1 => 'enero',
    2 => 'febrero',
    3 => 'marzo',
    4 => 'abril',
    5 => 'mayo',
    6 => 'junio',
    7 => 'julio',
    8 => 'agosto',
    9 => 'septiembre',
    10 => 'octubre',
    11 => 'noviembre',
    12 => 'diciembre',
];
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Noticias') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="padding: 20px;" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(!is_null($posts))
                        @foreach($posts as $post)
                            <div class="post">
                                <img src="{{ asset('storage/' . $post->banner) }}" alt="{{ $post->title }}">
                                <a href="{{ route('post.show', $post->slug) }}"><h2>{{$post->title}}</h2></a>
                                <p class="excerpt">{{ $post->excerpt }}</p>
                                <div class="fecha">
                                    <?php
                                        $timestamp = strtotime($post->published_at);

                                        echo date('j', $timestamp) . ' de ' . $months[date('n', $timestamp)] . ' del ' . date('Y', $timestamp);
                                        ?>
                                </div>
                                <a href="{{ route('post.show', $post->slug) }}" class="btn btn-primary read-more">Leer Más...</a>
                            </div>
                        @endforeach
                    @else
                        <h2>No hay noticias para mostrar.</h2>
                    @endif
                        <div class="mt-4">
                            @if(!is_null($posts))
                                {{ $posts->links() }}
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<style>

    /* Reset default margin and padding */
    body, h1, h2, h3, h4, p, div {
        margin: 0;
        padding: 0;
    }

    /* Typography */
    body {
        font-family: Arial, sans-serif;
    }

    h1 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    h2 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    h3 {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    h4 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    p {
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    /* Text styles */
    b {
        font-weight: bold;
    }

    i {
        font-style: italic;
    }

    /* Images */
    img {
        max-width: 100%;
        height: auto;
        margin-bottom: 1rem;
    }


    main div {
        margin-bottom: 20px;
    }
    .post {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
    }
    .post img {
        max-width: 100%;
        height: auto;
    }
    .post .excerpt {
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .post .read-more {
        margin-top: 20px;
    }

    .read-more {
        padding: 10px;
        background: #b37f49;
        color: white;
        margin-top: 20px;
    }
    .read-more:hover {
        padding: 10px;
        background: white;
        color: #b37f49;
        margin-top: 20px;
    }
</style>




