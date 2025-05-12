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
                            <div class="post bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 mb-8">
                                @if ($post->banner != null)
                                    <img src="{{ asset('storage/' . $post->banner) }}" alt="{{ $post->title }}" class="w-full h-auto rounded-md mb-4">
                                @endif

                                <a href="{{ route('post.show', $post->slug) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    <h2 class="text-2xl font-bold mb-2">{{$post->title}}</h2>
                                </a>
                                @if($post->excerpt)
                                <p class="excerpt text-gray-700 dark:text-gray-300 mb-3">{{ $post->excerpt }}</p>
                                @endif
                                <div class="fecha text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    <?php
                                        $timestamp = strtotime($post->published_at);

                                        // $months array is defined at the top of this file
                                        echo date('j', $timestamp) . ' de ' . $months[date('n', $timestamp)] . ' del ' . date('Y', $timestamp);
                                        ?>
                                </div>
                                <a href="{{ route('post.show', $post->slug) }}" class="read-more inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-sm font-medium">Leer Más...</a>
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
    /* Custom styles for noticias.blade.php can be added here if needed. */
    /* Most styling is now handled by Tailwind CSS utility classes in the markup. */
</style>




