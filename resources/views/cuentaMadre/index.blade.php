<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cuenta Madre') }}
        </h2>
    </x-slot>


    <style>
        select {
            color: black;
            background-color: white;
        }
    </style>

<form action="/cuenta-madre/dashboard" method="POST">
    @csrf
    <label for="user-select">Choose a user:</label>
    <select name="user_id" id="user-select">
        {!! $options !!}
    </select>
    <button type="submit">Submit</button>
</form>

</x-app-layout>
