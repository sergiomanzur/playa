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
            <div class="inside bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>{{ __($post->title) }}</h1>
                    @if(!is_null($post))
                        @if(!empty($post->banner))
                        <div class="featured-image">
                            <img src="{{ Storage::url($post->banner) }}" alt="{{ $post->title }}">
                        </div>
                        @endif
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
        /* Base styles */
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333; /* Default light mode text color */
            background-color: #f9fafb; /* Light gray background for light mode */
            margin: 0;
            padding: 0;
        }

        /* Main content container */
        .inside { /* This class is on the div with bg-white dark:bg-gray-800 */
            padding: 2rem !important; /* Overrides inline style="padding: 20px;" for more space. Consider removing inline style and using p-8 class. */
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Softer, more modern shadow */
        }
        .dark .inside {
            background-color: #1f2937; /* Tailwind's gray-800, ensuring consistency */
        }

        /* Post title - targets the main H1 */
        .text-gray-900.dark\\:text-gray-100 > h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.75rem; /* Adjusted spacing */
            line-height: 1.2;
            color: #111827; /* Tailwind's gray-900 */
        }
        .dark .text-gray-900.dark\\:text-gray-100 > h1 {
            color: #f3f4f6; /* Tailwind's gray-100 */
        }

        /* Featured Image */
        .featured-image {
            margin-bottom: 1.5rem;
            margin-top: 0.5rem; /* Space from title */
        }
        .featured-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); /* Subtle shadow for the image */
            display: block; /* Ensure it behaves as a block for margin auto if needed */
            margin-left: auto;
            margin-right: auto;
        }

        /* Post Date */
        .post-date {
            font-size: 0.9rem;
            color: #6b7280; /* Tailwind's gray-500 */
            margin-bottom: 2.5rem; /* More space before content */
        }
        .dark .post-date {
            color: #9ca3af; /* Tailwind's gray-400 for dark mode */
        }

        /* Post Content Area */
        .post-content {
            font-size: 1.1rem; /* Slightly larger base font for readability */
            color: #374151; /* Tailwind's gray-700 */
            line-height: 1.7;
        }
        .dark .post-content {
            color: #e5e7eb; /* Tailwind's gray-200 for dark mode (was gray-300) */
        }

        .post-content h1,
        .post-content h2,
        .post-content h3,
        .post-content h4,
        .post-content h5,
        .post-content h6 {
            font-weight: 600;
            margin-top: 2.2em;
            margin-bottom: 1em;
            line-height: 1.3;
            color: #111827; /* Tailwind's gray-900 */
        }
        .dark .post-content h1,
        .dark .post-content h2,
        .dark .post-content h3,
        .dark .post-content h4,
        .dark .post-content h5,
        .dark .post-content h6 {
            color: #f3f4f6; /* Tailwind's gray-100 */
        }

        .post-content h1 { font-size: 2.2rem; }
        .post-content h2 { font-size: 1.8rem; }
        .post-content h3 { font-size: 1.5rem; }
        .post-content h4 { font-size: 1.25rem; }

        .post-content p {
            margin-bottom: 1.25em;
        }

        .post-content a {
            color: #2563eb; /* Tailwind's blue-600 */
            text-decoration: none;
            border-bottom: 1px solid #93c5fd; /* Tailwind's blue-300 */
            transition: color 0.2s ease, border-color 0.2s ease;
        }
        .post-content a:hover {
            color: #1d4ed8; /* Tailwind's blue-700 */
            border-bottom-color: #60a5fa; /* Tailwind's blue-400 */
        }
        .dark .post-content a {
            color: #60a5fa; /* Tailwind's blue-400 for dark mode */
            border-bottom-color: #3b82f6; /* Tailwind's blue-500 */
        }
        .dark .post-content a:hover {
            color: #93c5fd; /* Tailwind's blue-300 */
            border-bottom-color: #2563eb; /* Tailwind's blue-600 */
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin-top: 1.5em;
            margin-bottom: 0.5em; /* Reduced bottom margin if figcaption follows */
            display: block;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }

        .post-content figcaption {
            display: none;
            font-size: 0.9rem;
            color: #6b7280; /* Tailwind's gray-500 */
            text-align: center;
            margin-top: 0.25em; /* Closer to image */
            margin-bottom: 1.5em;
        }
        .dark .post-content figcaption {
            color: #9ca3af; /* Tailwind's gray-400 */
        }

        .post-content blockquote {
            border-left: 3px solid #60a5fa; /* Accent color (Tailwind blue-400) */
            padding-left: 1.5em;
            margin-left: 0;
            margin-right: 0;
            margin-top: 1.5em;
            margin-bottom: 1.5em;
            font-style: italic;
            color: #4b5563; /* Tailwind's gray-600 */
        }
        .dark .post-content blockquote {
            border-left-color: #3b82f6; /* Tailwind's blue-500 for dark */
            color: #e5e7eb; /* Tailwind's gray-200 (was gray-300) */
        }

        .post-content ul,
        .post-content ol {
            margin-left: 1.5em;
            margin-bottom: 1.25em;
            padding-left: 1em; /* Added padding for list items */
        }
        .post-content li {
            margin-bottom: 0.6em;
        }

        .post-content pre {
            background-color: #f3f4f6; /* Tailwind's gray-100 */
            padding: 1.25em; /* More padding */
            border-radius: 8px; /* More rounded */
            overflow-x: auto;
            margin-top: 1.5em;
            margin-bottom: 1.5em;
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
            font-size: 0.95em; /* Slightly adjusted size */
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }
        .dark .post-content pre {
            background-color: #111827; /* Darker for contrast (Tailwind gray-900) */
            color: #e5e7eb; /* Tailwind's gray-200 */
            box-shadow: inset 0 1px 3px rgba(255,255,255,0.05);
        }

        .post-content code { /* Inline code */
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
            background-color: rgba(209, 213, 219, 0.3); /* Lighter gray-200 with alpha */
            color: #1e293b; /* Darker text for inline code */
            padding: 0.2em 0.5em;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .dark .post-content code {
            background-color: rgba(55, 65, 81, 0.5); /* gray-700 with alpha */
            color: #e5e7eb; /* Tailwind's gray-200 */
        }
        .post-content pre code { /* Code within pre blocks */
            background-color: transparent;
            color: inherit; /* Inherit from pre */
            padding: 0;
            font-size: inherit;
            border-radius: 0;
        }

        /* Responsive iframe */
        iframe {
            max-width: 100%;
            margin: 2em auto; /* More vertical space, centered */
            display: block;
            border-radius: 8px; /* Rounded iframes */
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .inside {
                padding: 1.5rem !important;
            }
            .text-gray-900.dark\\:text-gray-100 > h1 {
                font-size: 2.2rem; /* Adjust title for smaller screens */
            }
            .post-content {
                font-size: 1rem; /* Adjust content font for smaller screens */
            }
            .post-content h1 { font-size: 1.9rem; }
            .post-content h2 { font-size: 1.6rem; }
            .post-content h3 { font-size: 1.3rem; }
            .post-content pre {
                padding: 1em;
                font-size: 0.9em;
            }
        }
    </style>

</x-app-layout>




