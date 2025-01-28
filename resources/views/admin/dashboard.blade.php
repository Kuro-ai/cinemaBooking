<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-800 pt-6 pb-10">
        <div class="space-y-6 px-6">
            <!-- Top Row (3 Links in a Row) -->
            <div class="flex space-x-8">
                <a href="{{ route('admin.cinemas') }}" 
                   class="flex flex-col items-center justify-center w-44 h-44 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 11.25V6.75m6 4.5V6.75M3.75 21h16.5a1.5 1.5 0 001.5-1.5V7.5a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 7.5v12a1.5 1.5 0 001.5 1.5z" />
                    </svg>
                    <span>Manage Cinemas</span>
                </a>
                <a href="{{ route('admin.theatres') }}" 
                   class="flex flex-col items-center justify-center w-44 h-44 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5L12 4.5l3.75 3m-7.5 9l3.75 3 3.75-3M3 11.25h18" />
                    </svg>
                    <span>Manage Theatres</span>
                </a>
                <a href="{{ route('admin.movies') }}" 
                   class="flex flex-col items-center justify-center w-44 h-44 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v9m0 0l-3.75-3.75M12 16.5l3.75-3.75M18 12h2.25m-18 0H6m14.25 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Manage Movies</span>
                </a>
            </div>

            <!-- Bottom Row (2 Links in a Row) -->
            <div class="flex space-x-8">
                <a href="{{ route('admin.seats') }}" 
                   class="flex flex-col items-center justify-center w-44 h-44 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 15.75v1.5a2.25 2.25 0 002.25 2.25h1.5A2.25 2.25 0 0021 17.25v-7.5A2.25 2.25 0 0018.75 7.5h-1.5A2.25 2.25 0 0015 9.75v1.5m-6 7.5v1.5A2.25 2.25 0 009.75 21h1.5A2.25 2.25 0 0013.5 18.75v-7.5A2.25 2.25 0 0011.25 9h-1.5A2.25 2.25 0 007.5 11.25v1.5" />
                    </svg>
                    <span>Manage Seats</span>
                </a>
                <a href="{{ route('admin.manage-schedules') }}" 
                   class="flex flex-col items-center justify-center w-44 h-44 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6H6m6 0h6" />
                    </svg>
                    <span>Manage Schedules</span>
                </a>
                <a href="{{ route('admin.manage-foods') }}" 
                   class="flex flex-col items-center justify-center w-44 h-44 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6H6m6 0h6" />
                    </svg>
                    <span>Manage Foods</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
