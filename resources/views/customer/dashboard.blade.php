<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-800 dark:text-gray-200 overflow-hidden shadow-xl sm:rounded-lg">
                <p class="p-6">Welcome to the customer dashboard</p>

                <!-- Link to view cinemas -->
                <div class="mt-4">
                    <a href="{{ route('cinemas.list') }}" class="text-blue-500 hover:underline">View Cinemas</a>
                </div>

                <!-- Link to view movies -->
                <div class="mt-4">
                    <a href="{{ route('movies.list') }}" class="text-blue-500 hover:underline">View Movies and Book Seats</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
