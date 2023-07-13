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
            <a href="/blog/noticias"> {{ 'Noticias' }} </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="padding: 20px;" class="inside bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>{{ __($post->title) }}</h1>
                    @if(!is_null($post))
                        <div class="featured-image">
                            <img src="{{ asset('storage/' . $post->banner) }}" alt="{{ $post->title }}">
                        </div>
                        <div class="post-date">
                            <?php
                                $timestamp = strtotime($post->published_at);
                                echo date('j', $timestamp) . ' de ' . $months[date('n', $timestamp)] . ' del ' . date('Y', $timestamp);
                                ?>
                        </div>
                        <div class="post-content">
                            {!! html_entity_decode($post->content) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .post-date {
            color: lightgray;
        }
        figcaption {
            margin-top: 0px;
            margin-bottom: 5px;
            color: gray;
        }

        main .py-12, main .inside, main .max-w-7xl {
            margin-top: 0;
            margin-bottom: 0;
        }

        @media (min-width: 768px) {
            header {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 844px) {
            iframe {
                width: 100%;
            }
        }

        iframe {
            margin: auto;
        }

    </style>

</x-app-layout>




