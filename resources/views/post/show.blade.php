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
            <a href="/blog/noticias"> {{ __($post->title) }} </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="padding: 20px;" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                            {!! $post->content !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        div {
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
    </style>

</x-app-layout>




