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
            Noticias
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="padding: 20px;" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(!is_null($posts))
                        @foreach($posts as $post)
                            <div class="post">
                                @if(!empty($post->banner))
                                <img src="{{ Storage::url($post->banner) }}" alt="{{ $post->title }}" class="banner">
                                @endif
                                <div class="post-content-wrapper">
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
                            </div>
                        @endforeach
                    @else
                        <h2>No hay noticias para mostrar.</h2>
                    @endif
                </div>
                <div class="mt-4">
                    @if(!is_null($posts))
                        {{ $posts->links() }}
                    @endif
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

    /* NEW STYLES FOR MODERN CARD LAYOUT */
    .p-6.text-gray-900.dark\:text-gray-100 {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Responsive grid */
        gap: 2rem; /* Space between cards */
    }

    .post {
        background-color: #fff; /* Light mode card background */
        border-radius: 12px; /* Rounded corners for the card */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Softer, more prominent shadow */
        overflow: hidden; /* Ensures banner corners are clipped if image is larger */
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 0; /* Removed old margin, gap handles spacing */
        padding: 0; /* Remove old padding, content wrapper will handle it */
        border: none; /* Remove old border */
    }

    .dark .post {
        background-color: #2d3748; /* Dark mode card background (Tailwind gray-700) */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .post:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    .dark .post:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
    }

    .post img.banner {
        width: 100%;
        height: 200px; /* Fixed height for banners */
        object-fit: cover; /* Ensures image covers the area, might crop */
        margin-bottom: 0; /* Remove default img margin */
    }

    .post .post-content-wrapper {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1; /* Allows content to fill space, pushing button down */
    }

    .post h2 {
        font-size: 1.5rem; /* Slightly smaller for card titles */
        font-weight: 600; /* Semi-bold */
        margin-bottom: 0.75rem;
        color: #1a202c; /* Tailwind gray-800 */
    }
    .dark .post h2 {
        color: #f7fafc; /* Tailwind gray-100 */
    }
    .post a {
        text-decoration: none;
    }
    .post a:hover h2 {
        color: rgba(28, 152, 131, 1); /* Accent color on hover */
    }
    .dark .post a:hover h2 {
        color: #38b2ac; /* Lighter accent for dark mode (Tailwind teal-400) */
    }

    .post .excerpt {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #4a5568; /* Tailwind gray-700 */
        margin-top: 0;
        margin-bottom: 1rem;
        flex-grow: 1; /* Allows excerpt to push date and button down */
    }
    .dark .post .excerpt {
        color: #a0aec0; /* Tailwind gray-400 */
    }

    .post .fecha {
        font-size: 0.8rem;
        color: #718096; /* Tailwind gray-500 */
        margin-bottom: 1rem;
    }
    .dark .post .fecha {
        color: #718096; /* Tailwind gray-500 - can be same or slightly lighter */
    }

    .read-more {
        padding: 0.75rem 1.5rem;
        background: rgba(28, 152, 131, 1); /* Existing green */
        color: white;
        text-align: center;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block; /* Or block if you want full width */
        margin-top: auto; /* Pushes button to the bottom of the card */
        transition: background-color 0.3s ease;
    }

    .read-more:hover {
        background: rgb(22, 120, 103); /* Darker shade of green */
        color: white;
    }
    .dark .read-more {
        background: #38b2ac; /* Tailwind teal-400 for dark mode */
        color: #1a202c; /* Dark text for contrast on light button */
    }
    .dark .read-more:hover {
        background: #319795; /* Tailwind teal-500 */
        color: #1a202c;
    }

    /* Remove old specific .post styles if they conflict */
    /* .post img { max-width: 100%; height: auto; } */ /* Handled by img.banner */
    /* .post .excerpt { margin-top: 10px; margin-bottom: 20px; } */ /* Handled by new .post .excerpt */
    /* .post .read-more { margin-top: 20px; } */ /* Handled by new .read-more and flex layout */

    /* Pagination Styling - More Targeted */
    .mt-4 nav { /* The main nav element for pagination */
        display: flex;
        flex-direction: column; /* Stacks "Showing..." text (if present) and page links */
        align-items: center; /* Center the pagination block */
        margin-top: 2rem;
        gap: 0.75rem; /* Space between "Showing..." text and the links block */
    }

    /* Styling for the container of page numbers/links (desktop view) */
    /* This targets direct children of the typical Tailwind pagination button container */
    .mt-4 nav .relative.inline-flex.shadow-sm > *,
    /* Styling for mobile prev/next buttons */
    .mt-4 nav > .sm\\:hidden > a,
    .mt-4 nav > .sm\\:hidden > span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem; /* Tailwind's px-3 py-2 equivalent */
        margin: 0 0.125rem; /* Tailwind's mx-0.5 equivalent */
        font-size: 0.875rem; /* text-sm */
        line-height: 1.25rem; /* leading-5 */
        text-decoration: none;
        border: 1px solid #d1d5db; /* gray-300 */
        border-radius: 0.375rem; /* rounded-md */
        background-color: #ffffff; /* white */
        color: #374151; /* gray-700 */
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, color 0.15s ease-in-out;
    }

    /* Dark mode for pagination items */
    .dark .mt-4 nav .relative.inline-flex.shadow-sm > *,
    .dark .mt-4 nav > .sm\\:hidden > a,
    .dark .mt-4 nav > .sm\\:hidden > span {
        border-color: #4b5563; /* gray-600 */
        background-color: #374151; /* gray-700 */
        color: #d1d5db; /* gray-300 */
    }

    /* Hover state for clickable links (<a> tags) */
    .mt-4 nav .relative.inline-flex.shadow-sm > a:hover,
    .mt-4 nav > .sm\\:hidden > a:hover {
        border-color: #9ca3af; /* gray-400 */
        background-color: #f9fafb; /* gray-50 */
        color: #1f2937; /* gray-800 */
    }
    .dark .mt-4 nav .relative.inline-flex.shadow-sm > a:hover,
    .dark .mt-4 nav > .sm\\:hidden > a:hover {
        border-color: #6b7280; /* gray-500 */
        background-color: #4b5563; /* gray-600 */
        color: #f3f4f6; /* gray-100 */
    }

    /* Active/Current page item (usually has aria-current="page") */
    .mt-4 nav [aria-current="page"] {
        border-color: rgba(28, 152, 131, 1) !important; /* Use important to override Tailwind if necessary */
        background-color: rgba(28, 152, 131, 1) !important;
        color: #ffffff !important;
        font-weight: 600; /* Make current page font bolder */
    }
    .dark .mt-4 nav [aria-current="page"] {
        border-color: #38b2ac !important; /* teal-400 */
        background-color: #38b2ac !important;
        color: #1a202c !important; /* gray-900 for contrast */
    }

    /* Disabled items (usually <span> tags, e.g., non-clickable prev/next or ellipsis) */
    .mt-4 nav .relative.inline-flex.shadow-sm > span:not([aria-current="page"]),
    .mt-4 nav > .sm\\:hidden > span { /* Mobile disabled spans */
        color: #6b7280; /* gray-500 */
        cursor: default;
    }
    .dark .mt-4 nav .relative.inline-flex.shadow-sm > span:not([aria-current="page"]),
    .dark .mt-4 nav > .sm\\:hidden > span {
        color: #9ca3af; /* gray-400 */
    }

    /* Ensure "Showing X to Y of Z results" text is not styled as a button */
    .mt-4 nav p.text-sm {
        padding: 0 !important; /* Override any padding from broader rules */
        margin: 0 !important; /* Override any margin */
        border: none !important; /* Override any border */
        background-color: transparent !important; /* Override any background */
        color: #4a5568; /* gray-700, ensure it's not the button color */
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    .dark .mt-4 nav p.text-sm {
        color: #a0aec0; /* gray-400 */
    }
    /* Spans within the "Showing..." text */
    .mt-4 nav p.text-sm > span {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background-color: transparent !important;
        color: inherit !important; /* Inherit color from parent <p> */
        font-weight: 500; /* Tailwind's font-medium */
    }

</style>




