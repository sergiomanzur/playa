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

    <style>

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
            background: rgba(28, 152, 131, 1);
            color: white;
            margin-top: 20px;
        }
        .read-more:hover {
            padding: 10px;
            background: white;
            color: rgba(28, 152, 131, 1);
            margin-top: 20px;
        }
    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="padding: 20px;" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(!is_null($posts))
                        @foreach($posts as $post)
                            <div class="post">
                                <img src="{{ asset('storage/' . $post->banner) }}" alt="{{ $post->title }}">
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




