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
            <a href="/blog/noticias" class="hover:underline"> {{ 'Noticias' }} </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 sm:p-8">
                @if(!is_null($post))
                    @if($post->banner)
                        <div class="mb-6">
                            <img src="{{ asset('storage/' . $post->banner) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-[500px] object-contain rounded-lg shadow-md mx-auto">
                        </div>
                    @endif

                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-3 leading-tight">{{ $post->title }}</h1>

                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        <?php
                            $timestamp = strtotime($post->published_at);
                            // $months array is defined at the top of this file
                            echo date('j', $timestamp) . ' de ' . $months[date('n', $timestamp)] . ' del ' . date('Y', $timestamp);
                        ?>
                    </div>

                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-900 dark:text-gray-100 post-content">
                        {!! html_entity_decode($post->content) !!}
                    </div>
                @else
                    <p class="text-gray-900 dark:text-gray-100">Post no encontrado.</p>
                @endif
            </article>
        </div>
    </div>

    <style>
      /* Hide figcaptions as per request "no muestres la descripcion y peso" */
      .prose figcaption {
          display: none !important;
      }

      /* Ensure iframes are responsive, centered, and styled consistently */
      .prose iframe {
          width: 100%; /* Use full width of its container */
          max-width: 100%; /* Ensure it doesn't overflow */
          aspect-ratio: 16 / 9; /* Default aspect ratio for videos, adjust if needed */
          margin-top: 1.5em;
          margin-bottom: 1.5em;
          display: block; /* For centering and proper layout */
          margin-left: auto;
          margin-right: auto;
          border-radius: 0.375rem; /* Tailwind's rounded-md */
      }

      /* Ensure images within prose are centered. Prose usually handles this for block images. */
      .prose img {
          margin-left: auto;
          margin-right: auto;
          /* Tailwind Prose already adds other styles like rounded corners, top/bottom margins etc. */
      }
    </style>

</x-app-layout>




