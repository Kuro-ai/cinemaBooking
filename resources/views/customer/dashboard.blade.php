<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-900 dark:text-gray-200 leading-tight text-center">
            {{ __('🎬 Customer Dashboard') }}
        </h2>
    </x-slot>

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mt-3 mb-3 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mt-3 mb-3 text-center">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Dashboard Layout -->
    <div class="flex justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-900 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 p-6 max-w-5xl">

            @php
                $menuItems = [
                    [
                        'route' => 'movies.index',
                        'label' => 'Movies',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="text-gray-800 dark:text-gray-200" viewBox="0 0 16 16"><path d="M6 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0M1 3a2 2 0 1 0 4 0 2 2 0 0 0-4 0"/><path d="M9 6h.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 7.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 16H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm6 8.73V7.27l-3.5 1.555v4.35zM1 8v6a1 1 0 0 0 1 1h7.5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1"/><path d="M9 6a3 3 0 1 0 0-6 3 3 0 0 0 0 6M7 3a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/></svg>'
                    ],
                    [
                        'route' => 'customer.tickets',
                        'label' => 'Bookings',
                        'icon' => '<svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor" class="text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg"><path d="M16.5 6V6.75M16.5 9.75V10.5M16.5 13.5V14.25M16.5 17.25V18M7.5 12.75H12.75M7.5 15H10.5M3.375 5.25C2.75368 5.25 2.25 5.75368 2.25 6.375V9.40135C3.1467 9.92006 3.75 10.8896 3.75 12C3.75 13.1104 3.1467 14.0799 2.25 14.5987V17.625C2.25 18.2463 2.75368 18.75 3.375 18.75H20.625C21.2463 18.75 21.75 18.2463 21.75 17.625V14.5987C20.8533 14.0799 20.25 13.1104 20.25 12C20.25 10.8896 20.8533 9.92006 21.75 9.40135V6.375C21.75 5.75368 21.2463 5.25 20.625 5.25H3.375Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                    ],
                    [
                        'route' => 'foods.index',
                        'label' => 'Food & Drinks',
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="text-gray-800 dark:text-gray-200" viewBox="0 -960 960 960"><path d="M533-440q-32-45-84.5-62.5T340-520t-108.5 17.5T147-440zM40-360q0-109 91-174.5T340-600t209 65.5T640-360zm0 160v-80h600v80zM720-40v-80h56l56-560H450l-10-80h200v-160h80v160h200L854-98q-3 25-22 41.5T788-40zm0-80h56zM80-40q-17 0-28.5-11.5T40-80v-40h600v40q0 17-11.5 28.5T600-40zm260-400"/></svg>'
                    ]
                ];
            @endphp

            @foreach($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex flex-col items-center justify-center p-6 w-52 h-52 bg-white dark:bg-gray-800 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-300 relative overflow-hidden 
                    {{ request()->routeIs($item['route']) ? 'ring-4 ring-blue-500' : '' }}"
                   aria-label="{{ $item['label'] }}">

                    <!-- Glowing Effect on Active -->
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-400 to-indigo-500 opacity-10 transition-opacity duration-300 
                        {{ request()->routeIs($item['route']) ? 'opacity-30' : '' }}">
                    </div>

                    <!-- Icon -->
                    <div class="mb-6 relative z-10">{!! $item['icon'] !!}</div>

                    <!-- Label -->
                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-200 relative z-10">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
